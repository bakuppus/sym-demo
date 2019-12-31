<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Generator\Barcode;

use Faker\Generator;
use Faker\Provider\Barcode;
use Faker\Provider\Barcode as BaseBarcode;

final class BarcodeGenerator implements BarcodeEan13GeneratorInterface
{
    public function ean13(): string
    {
        $barcode = new Barcode(new Generator());

        return $barcode->ean13();
    }
}
