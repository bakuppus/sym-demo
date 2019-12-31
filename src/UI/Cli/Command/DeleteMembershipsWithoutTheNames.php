<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteMembershipsWithoutTheNames extends Command
{
    protected static $defaultName = 'memberships:delete-without-names';

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SymfonyStyle */
    private $symfonyStyle;

    /** @var int */
    private $deletedMemberships = 0;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @return int|null
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle = new SymfonyStyle($input, $output);

        $this->symfonyStyle->text('Deleting memberships without the names');

        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $memberships = $this
                        ->entityManager
                        ->getRepository(Membership::class)
                        ->findBy(['name' => null]);

        foreach ($memberships as $item) {
            if (null !== $item->getDeletedAt()) {
                continue;
            }

            $this->entityManager->remove($item);

            $this->increaseDeletedMembershipsCount();

            $this->symfonyStyle->text(sprintf('Deleted membership with id - %s', $item->getId()));
        }

        $this->entityManager->flush();

        $this->symfonyStyle->success(sprintf('Done, deleted %s membership(s)', $this->deletedMemberships));

        return 0;
    }

    private function increaseDeletedMembershipsCount()
    {
        ++$this->deletedMemberships;
    }
}
