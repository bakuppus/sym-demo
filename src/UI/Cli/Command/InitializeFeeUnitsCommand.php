<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Domain\Accounting\FeeUnit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeFeeUnitsCommand extends Command
{
    protected static $defaultName = 'fee-units:init';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * InitializeFeeUnitsCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fees are initializing...');

        foreach ($this->getFees() as $feeName) {
            $fee = $this->entityManager->getRepository(FeeUnit::class)->findOneBy(['name' => $feeName]);

            if (null !== $fee) {
                $output->writeln("{$feeName} was imported before.");
                continue;
            }

            $resource = new FeeUnit();
            $resource->setName($feeName);

            $this->entityManager->persist($resource);
        }

        $this->entityManager->flush();

        $output->writeln('Fees were initialized successfully!');

        return 0;
    }

    protected function getFees(): array
    {
        return [
            'Membership fee',
            'Player fee',
            'Cleaning fee',
            'Gym',
        ];
    }
}
