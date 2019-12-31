<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\CreatePayment;

use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class CreatePaymentCommandHandler implements MessageHandlerInterface
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

    public function __invoke(CreatePaymentCommand $command): PaymentInterface
    {
        $payment = $command->getResource();

        $this->entityManager->persist($payment);

        try {
            $this->workflow->get($payment, Payment::GRAPH)
                ->apply($payment, Payment::TRANSITION_CREATE);
        } catch (LogicException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $payment;
    }
}
