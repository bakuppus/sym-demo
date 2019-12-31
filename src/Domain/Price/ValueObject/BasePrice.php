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
class BasePrice extends PriceModule implements PriceStaticModuleInterface
{
    use PriceModuleTrait;
    use PriceModuleStaticTrait;

    /** @var string */
    protected $title = 'Base price';

    /** @var string */
    protected $description = 'Adjust the base price of the golf course.';

    /** @var int */
    protected $order = 0;

    /** @var bool */
    protected $active = true;

    /** @var array */
    protected $settings = [
        'workday_price' => 20000,
        'weekend_price' => 20000,
        'currency' => 'SEK',
    ];

    /** @var array */
    protected $settingsRules = [
        'workday_price' => 'required|integer|min:0|max:999999',
        'weekend_price' => 'required|integer|min:0|max:999999',
        'currency' => 'required|string|min:1',
    ];

    public function calculate(PriceDTO $priceDto, DateTime $priceDateTime): void
    {
        if (true === $this->isWeekend()) {
            $price = $this->settings['weekend_price'];
        } else {
            $price = $this->settings['workday_price'];
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'contribution' => $price,
        ];

        $priceDto->currency = $this->settings['currency'];
        $priceDto->price += $price;

        $priceDto->operations['BasePrice'] = $operation;
    }
}
