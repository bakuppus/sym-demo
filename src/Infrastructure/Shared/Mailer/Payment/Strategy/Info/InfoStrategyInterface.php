<?php

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Info;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Info;

interface InfoStrategyInterface
{
    public function supports(PaymentInterface $payment): bool;

    public function getInfo(PaymentInterface $payment): Info;
}