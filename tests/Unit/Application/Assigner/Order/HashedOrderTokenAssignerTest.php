<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Assigner\Order;

use App\Application\Assigner\Order\Token\HashedOrderTokenAssigner;
use App\Domain\Order\Order;
use Hashids\HashidsInterface;
use PHPUnit\Framework\TestCase;

final class HashedOrderTokenAssignerTest extends TestCase
{
    public function testTokenAssignIfNotSet(): void
    {
        $encoder = $this->createMock(HashidsInterface::class);
        $encoder->expects($this->once())->method('encode');

        $order = new Order();

        $assigner = new HashedOrderTokenAssigner($encoder);
        $assigner->assignTokenIfNotSet($order);
    }
}
