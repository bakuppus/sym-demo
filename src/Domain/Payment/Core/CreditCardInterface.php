<?php

declare(strict_types=1);

namespace App\Domain\Payment\Core;

use App\Domain\Payment\Component\CreditCardInterface as BaseCreditCardInterface;
use App\Domain\Player\Player;
use Doctrine\Common\Collections\Collection;

interface CreditCardInterface extends BaseCreditCardInterface
{
    public function getPayments(): Collection;

    public function getCustomer(): Player;
}