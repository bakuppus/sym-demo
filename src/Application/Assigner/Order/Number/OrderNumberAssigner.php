<?php

declare(strict_types=1);

namespace App\Application\Assigner\Order\Number;

use App\Domain\Order\Core\OrderInterface;
use App\Infrastructure\Shared\Generator\Barcode\BarcodeGenerator;

final class OrderNumberAssigner implements OrderNumberAssignerInterface
{
    /** @var BarcodeGenerator */
    private $barcode;

    public function __construct(BarcodeGenerator $barcode)
    {
        $this->barcode = $barcode;
    }

    public function assignNumber(OrderInterface $order): void
    {
        if (null !== $order->getNumber()) {
            return;
        }

        $order->setNumber($this->barcode->ean13());
    }
}
