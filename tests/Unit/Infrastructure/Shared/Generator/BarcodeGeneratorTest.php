<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Generator;

use App\Infrastructure\Shared\Generator\Barcode\BarcodeGenerator;
use PHPUnit\Framework\TestCase;

final class BarcodeGeneratorTest extends TestCase
{
    public function testEan13(): void
    {
        $generator = new BarcodeGenerator();
        $ean13 = $generator->ean13();

        $this->assertIsString($ean13);
        $this->assertEquals(13, strlen($ean13));
    }
}
