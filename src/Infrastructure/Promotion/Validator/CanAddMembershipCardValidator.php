<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Application\Command\Promotion\Crm\AddCardToMembership\AddCardToMembershipCommand;
use App\Domain\Promotion\Core\MembershipInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Cake\Chronos\Chronos;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class CanAddMembershipCardValidator extends ConstraintValidator
{
    /** @var EntityManager */
    private $entityManager;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var Constraint|CanAddMembershipCard */
    private $constraint;

    /**
     * HasActiveMembershipCardValidator constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface $messageBus
     */
    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    /**
     * @param AddCardToMembershipCommand $command
     * @param Constraint|CanAddMembershipCard $constraint
     */
    public function validate($command, Constraint $constraint): void
    {
        $this->constraint = $constraint;

        if (false === $command instanceof AddCardToMembershipCommand) {
            throw new InvalidArgumentException('Invalid object');
        }

        $membership = $command->getObjectToPopulate();
        if (false === $membership instanceof MembershipInterface) {
            throw new InvalidArgumentException(sprintf('Invalid object should be instance of %s', MembershipInterface::class));
        }

        if (false === $this->checkMembershipEligibility($membership)) {
            return;
        }

        $membershipCards = $this->getMembershipCards($command);

        if (false === $this->checkFutureOrUpcoming($membershipCards)) {
            return;
        }

        if (Membership::DURATION_ANNUAL === $command->durationType && null !== $command->calendarYear) {
            $this->checkIsCurrentOrNextYear($command->calendarYear);
            $this->checkValidDates($command->calendarYear, $membershipCards);
        }
    }

    private function checkMembershipEligibility(MembershipInterface $membership): bool
    {
        if (false === $membership->getIsActive() || Membership::STATE_PUBLISHED !== $membership->getState()) {
            $this->context
                ->buildViolation($this->constraint->messageInvalidMembership)
                ->atPath($this->constraint->membershipField)
                ->addViolation();

            return false;
        }

        return true;
    }

    private function getMembershipCards(AddCardToMembershipCommand $command): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('mc')
            ->from(MembershipCard::class, 'mc')
            ->where('mc.player = :player')
            ->andWhere('mc.club = :club')
            ->andWhere($qb->expr()->notIn('mc.state', [MembershipCard::STATE_DELETED, MembershipCard::STATE_CANCELED]))
            ->setParameter('player', $command->player)
            ->setParameter('club', $command->getObjectToPopulate()->getClub());

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @param MembershipCard[]|array $membershipCards
     *
     * @return bool
     */
    private function checkFutureOrUpcoming(array $membershipCards): bool
    {
        foreach ($membershipCards as $membershipCard) {
            if (false === $this->isValidMembershipCardStatus($membershipCard)) {
                return false;
            }
        }

        return true;
    }

    private function isValidMembershipCardStatus(MembershipCard $membershipCard): bool
    {
        $status = $membershipCard->getStatus();

        if (true === in_array($status, [MembershipCard::STATUS_FUTURE, MembershipCard::STATUS_UPCOMING])) {
            $this->context
                ->buildViolation($this->constraint->messageFutureOrUpcoming)
                ->atPath($this->constraint->playerField)
                ->addViolation();

            return false;
        }

        return true;
    }

    private function checkIsCurrentOrNextYear(string $calendarYear): void
    {
        if (false === $this->isCurrentOrNextYear($calendarYear)) {
            $this->context
                ->buildViolation($this->constraint->messageCurrentOrNextYear)
                ->atPath($this->constraint->calendarYear)
                ->addViolation();
        }
    }

    /**
     * @param string $calendarYear
     * @param array|MembershipCard[] $membershipCards
     */
    private function checkValidDates(string $calendarYear, array $membershipCards): void
    {
        $year = Chronos::createFromFormat('Y-m-d', $calendarYear)->startOfYear();

        foreach ($membershipCards as $membershipCard) {
            if (false === $this->isValidMembershipCardDates($membershipCard, $year)) {
                break;
            }
        }
    }

    private function isValidMembershipCardDates(MembershipCard $membershipCard, DateTimeInterface $year): bool
    {
        $expiresAt = Chronos::instance($membershipCard->getExpiresAt());
        $startsAt = $expiresAt->addYear(-1)->startOfYear();

        if (null !== $membershipCard->getStartsAt()) {
            $startsAt = Chronos::instance($membershipCard->getStartsAt());
        }

        if (true === $year->between($startsAt, $expiresAt)) {
            $this->context
                ->buildViolation($this->constraint->messageInvalidDates)
                ->atPath($this->constraint->durationType)
                ->addViolation();

            return false;
        }

        return true;
    }

    private function isCurrentOrNextYear(string $calendarYear): bool
    {
        return in_array($calendarYear, [
            Chronos::now()->startOfYear()->format('Y-m-d'),
            Chronos::now()->addYear()->startOfYear()->format('Y-m-d'),
        ]);
    }
}
