<?php

declare(strict_types=1);

namespace App\Domain\Price\ValueObject;

use App\Domain\Price\ValueObject\Price as PriceDTO;
use App\Domain\Price\PriceModule;
use App\Domain\Price\PriceModuleStaticTrait;
use App\Domain\Price\PriceModuleTrait;
use App\Domain\Price\PriceStaticModuleInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 */
class RoundPrice extends PriceModule implements PriceStaticModuleInterface
{
    use PriceModuleTrait;
    use PriceModuleStaticTrait;

    /** @var string */
    protected $title = 'Price rounding';

    /** @var string */
    protected $description = 'Rounds the price to a specified granularity.';

    /** @var int */
    protected $order = 15;

    /** @var bool */
    protected $active = true;

    /** @var array */
    protected $settings = [
        'workday_precision' => 500,
        'weekend_precision' => 500,
    ];

    /** @var array */
    protected $settingsRules = [
        'workday_precision' => 'required|integer|min:100|max:10000',
        'weekend_precision' => 'required|integer|min:100|max:10000',
    ];

    public function calculate(PriceDTO $priceDto, DateTime $priceDateTime): void
    {
        if (true === $this->isWeekend()) {
            $this->calculateWeekend($priceDto);
        } else {
            $this->calculateWorkday($priceDto);
        }
    }

    private function calculateWeekend(PriceDTO $priceDto): void
    {
        $precision = $this->settings['weekend_precision'];
        $price = round($priceDto->price / $precision) * $precision;

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'contribution' => $price,
        ];

        $priceDto->operations['RoundPrice'] = $operation;

        $priceDto->price = $price;
    }

    private function calculateWorkday(PriceDTO $priceDto): void
    {
        $precision = $this->settings['workday_precision'];
        $price = round($priceDto->price / $precision) * $precision;

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'contribution' => $price,
        ];

        $priceDto->operations['RoundPrice'] = $operation;

        $priceDto->price = $price;
    }
}
