<?php

declare(strict_types=1);

namespace App\Application\Command\Payment\SendReceipt;

use App\Domain\Payment\GatewayConfig;
use App\Infrastructure\Shared\Mailer\Payment\ReceiptSender;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendReceiptCommandHandler implements MessageHandlerInterface
{
    /** @var ReceiptSender */
    private $receiptSender;

    public function __construct(ReceiptSender $receiptSender)
    {
        $this->receiptSender = $receiptSender;
    }

    /**
     * @param SendReceiptCommand $command
     *
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendReceiptCommand $command): void
    {
        $payment = $command->getPayment();
        $paymentMethod = $payment->getPaymentMethod();
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (GatewayConfig::GATEWAY_NAME_OFFLINE !== $gatewayConfig->getGatewayName()) {
            $this->receiptSender->send($payment);
        }
    }
}