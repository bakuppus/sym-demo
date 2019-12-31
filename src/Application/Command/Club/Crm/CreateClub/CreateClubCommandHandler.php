<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\CreateClub;

use App\Domain\Club\Club;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateClubCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function __invoke(CreateClubCommand $command): Club
    {
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
