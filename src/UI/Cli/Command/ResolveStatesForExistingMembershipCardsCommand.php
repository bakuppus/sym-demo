<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Domain\Promotion\MembershipCard;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResolveStatesForExistingMembershipCardsCommand extends Command
{
    private const BATCH_SIZE = 500;

    protected static $defaultName = 'membership-card:resolve-states';

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->getFilters()->disable('softdeleteable');

        $symfonyStyle = new SymfonyStyle($input, $output);

        try {
            $countMembershipCards = $this->countMembershipCards();
        } catch (NoResultException $e) {
            $countMembershipCards = 0;
        } catch (NonUniqueResultException $e) {
            $countMembershipCards = 0;
        }

        $symfonyStyle->text("Membership cards found: {$countMembershipCards}");
        $progressBar = $symfonyStyle->createProgressBar($countMembershipCards);
        $iterableResult = $this->getMembershipCardsIterator();

        $i = 0;
        while (false !== ($row = $iterableResult->next())) {
            $membershipCard = $this->extractMembershipCard($row);
            $membershipCard->setState($membershipCard->getStateForOld());
            $this->entityManager->persist($membershipCard);

            if (0 === ($i % self::BATCH_SIZE)) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }

            ++$i;

            $progressBar->advance();
        }

        $this->entityManager->flush();
        $progressBar->finish();
        $symfonyStyle->success('All states are resolved!');
    }

    /**
     * @return int
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function countMembershipCards()
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select($qb->expr()->count('mc'))
            ->from(MembershipCard::class, 'mc')
            ->where($qb->expr()->isNull('mc.order'));

        $count = (int) $qb->getQuery()->getSingleScalarResult();

        return (int) $count;
    }

    private function getMembershipCardsIterator(): IterableResult
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('mc')
            ->from(MembershipCard::class, 'mc')
            ->where($qb->expr()->isNull('mc.order'));

        $query = $qb->getQuery();

        return $query->iterate();
    }

    private function extractMembershipCard(array $result): MembershipCard
    {
        return $result['0'];
    }
}
