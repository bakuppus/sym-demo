<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\AddNewMembership;

use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AddNewMembershipCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(AddNewMembershipCommand $command): Membership
    {
        $source = $command->getResource();
        $club = $this->entityManager->merge($source->getClub());
        $this->entityManager->refresh($club);
        $source->setClub($club);

        $this->entityManager->persist($source);

        return $source;
    }
}
