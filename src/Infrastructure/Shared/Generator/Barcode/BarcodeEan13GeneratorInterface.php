<?php

namespace App\Infrastructure\Shared\Generator\Barcode;

interface BarcodeEan13GeneratorInterface
{
    public function ean13(): string;
}
