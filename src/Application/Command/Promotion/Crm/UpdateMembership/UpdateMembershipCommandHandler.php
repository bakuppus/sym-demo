<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateMembership;

use App\Domain\Promotion\Membership;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateMembershipCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateMembershipCommand $command): Membership
    {
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
