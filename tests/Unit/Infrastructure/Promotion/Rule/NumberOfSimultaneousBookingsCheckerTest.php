<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Promotion\Rule;

use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Infrastructure\Promotion\Rule\NumberOfSimultaneousBookingsRuleChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class NumberOfSimultaneousBookingsCheckerTest extends TestCase
{
    public function testType()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);

        $ruleChecker = new NumberOfSimultaneousBookingsRuleChecker($messageBus);
        $this->assertEquals('number_of_simultaneous_bookings_checker', $ruleChecker->getType());
    }

    public function testValidateConfiguration()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);

        $configuration = ['number_of_simultaneous_bookings' => 10];

        $ruleChecker = new NumberOfSimultaneousBookingsRuleChecker($messageBus);
        $ruleChecker->validateConfiguration($configuration);

        $this->assertTrue(true);
    }

    public function testValidateWrongConfiguration()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);

        $this->expectException(InvalidConfigurablePromotionException::class);

        $configuration = ['12', false, 22];

        $ruleChecker = new NumberOfSimultaneousBookingsRuleChecker($messageBus);
        $ruleChecker->validateConfiguration($configuration);
    }
}
