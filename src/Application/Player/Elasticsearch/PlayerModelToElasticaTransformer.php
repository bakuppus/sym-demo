<?php

declare(strict_types=1);

namespace App\Application\Player\Elasticsearch;

use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Membership\PlayerMembershipToAssign;
use App\Domain\Player\Player;
use App\Domain\Promotion\MembershipCard;
use Closure;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Transformer\ModelToElasticaAutoTransformer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PlayerModelToElasticaTransformer extends ModelToElasticaAutoTransformer
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        array $options = [],
        EventDispatcherInterface $dispatcher = null
    ) {
        parent::__construct($options, $dispatcher);

        $this->entityManager = $entityManager;
    }

    public function setPropertyAccessor(PropertyAccessorInterface $propertyAccessor): void
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function transform($player, array $fields)
    {
        $identifier = $this->propertyAccessor->getValue($player, $this->options['identifier']);

        /** @var Player $player */
        $data = [
            'id' => $player->getId(),
            'email' => $player->getEmail(),
            'first_name' => $player->getFirstName(),
            'last_name' => $player->getLastName(),
            'related_club' => $this->getRelatedClub($player, $fields['related_club']['properties']),
            'golf_id' => $player->getGolfId(),
            'phone' => $player->getPhone(),
            'search_field' => $this->getSearchField($player),
        ];

        return new Document($identifier, $data);
    }

    public function getSearchField(Player $player): string
    {
        return implode(' ', [
            $player->getGolfId(),
            $player->getEmail(),
            $player->getFirstName(),
            $player->getLastName(),
            $player->getEmail(),
            $player->getPhone(),
        ]);
    }

    public function getRelatedClub(Player $player, array $fields): array
    {
        return [
            'last_played_bookings' => $this->getLastPlayedBookings($player, $fields['last_played_bookings']['properties']),
            'player_membership' => $this->getPlayerMembership($player, $fields['player_membership']['properties']),
            'player_membership_to_assign' => $this->getGitMembership($player, $fields['player_membership_to_assign']['properties']),
            'play_right' => $this->getPlayRight($player, $fields['play_right']['properties']),
        ];
    }

    public function getPlayRight(Player $player, array $fields): array
    {
        return $this->transformNested($player->getPlayRights(), $fields);
    }

    public function getGitMembership(Player $player, array $fields): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('pma')
            ->from(PlayerMembershipToAssign::class, 'pma')
            ->where('pma.player = :player')
            ->setParameter('player', $player);

        return $this->transformNested(
            $qb->getQuery()->getResult(),
            $fields,
            function (PlayerMembershipToAssign $playerMembershipToAssign, Document $document) {
                $club = $playerMembershipToAssign->getGolfClub();
                if (null === $club) {
                    return;
                }

                $document->set('golf_club_id', $club->getId());
            });
    }

    public function getPlayerMembership(Player $player, array $fields): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('msc')
            ->from(MembershipCard::class, 'msc')
            ->where('msc.player = :player')
            ->setParameter('player', $player);

        $result = array_filter($qb->getQuery()->getResult(), function (MembershipCard $membershipCard) {
            return MembershipCard::STATUS_OLD !== $membershipCard->getStatus();
        });

        return $this->transformNested(
            $result,
            $fields,
            function (MembershipCard $membershipCard, Document $document): void {
                $club = $membershipCard->getClub();
                if (null === $club) {
                    return;
                }

                $document->set('golf_club_id', $club->getId());
            });
    }

    public function getLastPlayedBookings(Player $player, array $fields): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('ttb')
            ->from(TeeTimeBooking::class, 'ttb')
            ->join('ttb.bookingParticipants', 'bp')
            ->andWhere('bp.player = :player')
            ->andWhere('ttb.deletedAt IS NULL')
            ->andWhere($qb->expr()->in('ttb.status', [TeeTimeBooking::STATUS_PAID, TeeTimeBooking::STATUS_PAY_ON_SITE]))
            ->setParameter('player', $player);

        return $this->transformNested(
            $qb->getQuery()->getResult(),
            $fields,
            function (TeeTimeBooking $booking, Document $document): void {
                $course = $booking->getCourse();
                if (null === $course) {
                    return;
                }

                $club = $course->getClub();
                if (null === $club) {
                    return;
                }

                $document->set('golf_club_id', $club->getId());
            });
    }

    protected function transformNested($objects, array $fields, Closure $afterTransformObject = null)
    {
        if (is_array($objects) || $objects instanceof \Traversable || $objects instanceof \ArrayAccess) {
            $documents = [];
            foreach ($objects as $object) {
                $document = $this->transformObjectToDocument($object, $fields);
                if (null !== $afterTransformObject) {
                    $afterTransformObject($object, $document);
                }
                $transformedData = $document->getData();
                $documents[] = $transformedData;
            }

            return $documents;
        } elseif (null !== $objects) {
            $document = $this->transformObjectToDocument($objects, $fields);

            return $document->getData();
        }

        return [];
    }
}
