<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use Payum\Core\Model\Token;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PaymentToken extends Token
{
}