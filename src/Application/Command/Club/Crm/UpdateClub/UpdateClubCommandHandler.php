<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\UpdateClub;

use App\Domain\Club\Club;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateClubCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateClubCommand $command): Club
    {
        $source = $command->getResource();

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
