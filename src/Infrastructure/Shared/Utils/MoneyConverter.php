<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Utils;

trait MoneyConverter
{
    public function centsToMoney(int $centsAmount): float
    {
        return $centsAmount / 100;
    }
}