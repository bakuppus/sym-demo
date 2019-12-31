<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateMembershipFee;

use App\Domain\Accounting\FeeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateMembershipFeeCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateMembershipFeeCommand $command): FeeInterface
    {
        $resource = $command->getResource();
        $this->entityManager->persist($resource);

        return $resource;
    }
}
