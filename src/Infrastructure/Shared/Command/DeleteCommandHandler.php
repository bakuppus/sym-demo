<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

final class DeleteCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var Registry */
    private $workflow;

    public function __construct(EntityManagerInterface $entityManager, Registry $workflow)
    {
        $this->entityManager = $entityManager;
        $this->workflow = $workflow;
    }

    public function __invoke(DeleteCommandAwareInterface $command): void
    {
        if (true === $command instanceof DeleteCommandWorkflowInterface) {
            try {
                $this->workflow->get($command, $command->getWorkflow())
                    ->apply($command, $command->removeTransitionName());
            } catch (LogicException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
        }

        $this->entityManager->remove($command);
        $this->entityManager->flush();
    }
}
