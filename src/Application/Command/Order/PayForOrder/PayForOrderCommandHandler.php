<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PayForOrder;

use App\Application\Command\Payment\ChargePayment\ChargePaymentCommand;
use App\Application\Command\Payment\CreatePayment\CreatePaymentCommand;
use App\Application\Service\Payment\Exception\PaymentCriticalException;
use App\Application\Service\Payment\Exception\PaymentLogicException;
use App\Application\Service\Payment\Strategy\UpdatePaymentMethod\UpdatePaymentMethodContext;
use App\Domain\Order\Order;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class PayForOrderCommandHandler implements MessageHandlerInterface
{
    /** @var ManagerRegistry $registry */
    private $registry;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var UpdatePaymentMethodContext */
    private $updatePaymentMethodContext;

    public function __construct(
        ManagerRegistry $registry,
        MessageBusInterface $messageBus,
        UpdatePaymentMethodContext $updatePaymentMethodContext
    ) {
        $this->registry = $registry;
        $this->messageBus = $messageBus;
        $this->updatePaymentMethodContext = $updatePaymentMethodContext;
    }

    public function __invoke(PayForOrderCommand $command): PaymentInterface
    {
        $order = $command->order;

        if (Order::STATE_NEW !== $order->getState()) {
            throw new BadRequestHttpException('Order has wrong state');
        }

        if (Order::PAYMENT_STATE_AWAITING_PAYMENT !== $order->getPaymentState()) {
            throw new BadRequestHttpException('Order has wrong payment state');
        }

        $payments = $order->getPayments();

        if (true === $payments->isEmpty()) {
            throw new NotFoundHttpException('Payment not found');
        }

        /** @var Payment $payment */
        $payment = $payments->last();

        if (Payment::STATE_FAILED === $payment->getState()) {
            $createPayment = new CreatePaymentCommand();

            $createPayment->setOrder($order);
            $createPayment->setAmount($order->getTotal());
            $createPayment->setCurrencyCode($order->getCurrencyCode());

            $envelope = $this->messageBus->dispatch($createPayment);

            $handledStamp = $envelope->last(HandledStamp::class);
            /** @var PaymentInterface $payment */
            $payment = $handledStamp->getResult();

            $order->addPayment($payment);
        }

        try {
            $payment = $this->updatePaymentMethodContext->updatePaymentMethod($payment, $command->method);
        } catch (PaymentLogicException | PaymentCriticalException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        /**
         * @TODO: Think about moving message dispatch to some event like workflow or custom
         * @TODO: Think about multiple payments may be refactor needed
         */
        $chargePaymentCommand = new ChargePaymentCommand();
        $chargePaymentCommand->setPayment($payment);
        $chargePaymentCommand->setOptions($command->parameters);

        $this->messageBus->dispatch($chargePaymentCommand);

        return $payment;
    }
}