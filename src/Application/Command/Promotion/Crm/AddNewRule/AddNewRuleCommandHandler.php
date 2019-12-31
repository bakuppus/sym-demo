<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewRule;

use App\Domain\Promotion\PromotionRule;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AddNewRuleCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddNewRuleCommand $command
     *
     * @return PromotionRule
     */
    public function __invoke(AddNewRuleCommand $command): PromotionRule
    {
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}