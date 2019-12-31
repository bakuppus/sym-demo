<?php

declare(strict_types=1);

namespace App\Application\Query\TeeTimeBooking\LimitQuery;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Domain\Booking\TeeTimeBookingLimit;
use Doctrine\ORM\NonUniqueResultException;

class LimitQueryHandler implements MessageHandlerInterface
{
    /** @var ManagerRegistry */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(LimitQuery $query): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this
            ->managerRegistry
            ->getRepository(TeeTimeBookingLimit::class)
            ->createQueryBuilder('bl');

        $qb
            ->select($qb->expr()->count('bl.id'))
            ->where($qb->expr()->eq('bl.player', ':player'))
            ->andWhere($qb->expr()->eq('bl.course', ':course'))
            ->andWhere($qb->expr()->eq('bl.membership', ':membership'))
            ->setParameter('player', $query->player->getId())
            ->setParameter('course', $query->course->getId())
            ->setParameter('membership', $query->membership->getId());

        try {
            return (int) $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }
}
