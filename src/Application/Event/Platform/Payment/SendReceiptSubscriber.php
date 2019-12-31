<?php

declare(strict_types=1);

namespace App\Application\Event\Platform\Payment;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Application\Command\Order\PayForOrder\PayForOrderCommand;
use App\Application\Command\Payment\ChargePayment\ChargePaymentCommand;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use App\Infrastructure\Shared\Mailer\Order\OrderMembershipPaymentLinkSender;
use App\Infrastructure\Shared\Mailer\Payment\ReceiptSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class SendReceiptSubscriber implements EventSubscriberInterface
{
    /** @var OrderMembershipPaymentLinkSender */
    private $receiptSender;

    /** @var PaymentInterface */
    private $subject;

    /** @var ChargePaymentCommand */
    private $command;

    public function __construct(ReceiptSender $receiptSender)
    {
        $this->receiptSender = $receiptSender;
    }

    /**
     * {@inheritDoc}
     * @throws TransportExceptionInterface
     */
    public function sendReceipt(ViewEvent $event)
    {
        $this->subject = $event->getControllerResult();
        $this->command = $event->getRequest()->attributes->get('data');
        $method = $event->getRequest()->getMethod();

        if (null === $this->command || false === $this->command instanceof PayForOrderCommand) {
            return;
        }

        if (
            false === $this->subject instanceof PaymentInterface
            || $method !== Request::METHOD_PUT
        ) {
            return;
        }

        if (Payment::STATE_COMPLETED !== $this->subject->getState()) {
            return;
        }

        $this->receiptSender->send($this->subject);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendReceipt', EventPriorities::POST_WRITE],
        ];
    }
}
