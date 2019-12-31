<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Info;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Payment\Payum\Braintree\BraintreeGateway;
use App\Infrastructure\Shared\Mailer\Payment\Model\Info;
use Carbon\CarbonImmutable;

final class BraintreeInfoStrategy implements InfoStrategyInterface
{
    public function getInfo(PaymentInterface $payment): Info
    {
        $paymentDetails = $payment->getDetails();

        $transactionAmount = str_replace(".", ',', $paymentDetails['transaction']['amount']);

        /**
         * @TODO Set dynamic timezone
         */
        $transactionDate = CarbonImmutable::parse($paymentDetails['transaction']['updatedAt'])
            ->setTimezone('Europe/Stockholm');

        $cardLastFour = $paymentDetails['transaction']['creditCard']['last4'];
        $cardBrand = $paymentDetails['transaction']['creditCard']['cardType'];

        $customer = $payment->getOrder()->getCustomer();

        $info = new Info();
        $info->setTransactionAmount($transactionAmount);
        $info->setTransactionDate($transactionDate);
        $info->setCustomerEmail($customer->getExistingEmail());
        $info->setCustomerName($customer->getFullName());
        $info->setCardBrand($cardBrand);
        $info->setCardLastFour($cardLastFour);

        return $info;
    }

    public function supports(PaymentInterface $payment): bool
    {
        $gatewayConfig = $payment->getPaymentMethod()->getGatewayConfig();

        $gatewayName = $gatewayConfig->getGatewayName();

        if (
            BraintreeGateway::SANDBOX_GATEWAY_NAME !== $gatewayName
            && BraintreeGateway::PRODUCTION_GATEWAY_NAME !== $gatewayName
        ) {
            return false;
        }

        if (true === empty($payment->getDetails())) {
            return false;
        }

        return true;
    }
}