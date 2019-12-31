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
class MinPrice extends PriceModule implements PriceStaticModuleInterface
{
    use PriceModuleTrait;
    use PriceModuleStaticTrait;

    /** @var string */
    private $title = 'Minimum price';

    /** @var string */
    private $description = 'Set minimum price.';

    /** @var int */
    private $order = 20;

    /** @var bool */
    private $active = true;

    /** @var array */
    private $settings = [
        'workday_min' => 10000,
        'weekend_min' => 10000,
    ];

    /** @var array */
    private $settingsRules = [
        'workday_min' => 'required|integer|min:0|max:999999',
        'weekend_min' => 'required|integer|min:0|max:999999',
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
        $price = $priceDto->price;

        if ($priceDto->price < $this->settings['weekend_min']) {
            $price = $this->settings['weekend_min'];
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'contribution' => $price - $priceDto->price,
        ];

        $priceDto->price = $price;

        $priceDto->operations['MinPrice'] = $operation;
    }

    private function calculateWorkday(PriceDTO $priceDto): void
    {
        $price = $priceDto->price;

        if ($priceDto->price < $this->settings['workday_min']) {
            $price = $this->settings['workday_min'];
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'contribution' => $price - $priceDto->price,
        ];

        $priceDto->price = $price;

        $priceDto->operations['MinPrice'] = $operation;
    }
}
