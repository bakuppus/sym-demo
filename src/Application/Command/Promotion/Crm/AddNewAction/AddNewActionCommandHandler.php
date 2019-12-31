<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewAction;

use App\Domain\Promotion\PromotionAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddNewActionCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param AddNewActionCommand $command
     *
     * @return PromotionAction
     */
    public function __invoke(AddNewActionCommand $command): PromotionAction
    {
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}