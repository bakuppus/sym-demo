<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewMembershipFee;

use App\Domain\Promotion\MembershipFee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AddNewMembershipFeeCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(AddNewMembershipFeeCommand $command): MembershipFee
    {
        $resource = $command->getResource();
        $this->entityManager->persist($resource);

        return $resource;
    }
}
