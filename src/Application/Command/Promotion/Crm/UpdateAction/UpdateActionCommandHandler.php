<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateAction;

use App\Domain\Promotion\PromotionAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UpdateActionCommandHandler
 * @package App\Application\Command\Promotion\Crm\UpdateAction
 */
final class UpdateActionCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UpdateActionCommand $command
     *
     * @return PromotionAction
     */
    public function __invoke(UpdateActionCommand $command): PromotionAction
    {
        /** @var PromotionAction $source */
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
