<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PaymentLink;

use App\Infrastructure\Shared\Mailer\Order\OrderMembershipPaymentLinkSender;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendPaymentLinkCommandHandler implements MessageHandlerInterface
{
    /**
     * @var OrderMembershipPaymentLinkSender
     */
    private $mailer;

    public function __construct(OrderMembershipPaymentLinkSender $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendPaymentLinkCommand $command): void
    {
        $this->mailer->send($command->getResource());

        return;
    }
}
