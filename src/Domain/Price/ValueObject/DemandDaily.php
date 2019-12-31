<?php

declare(strict_types=1);

namespace App\Domain\Price\ValueObject;

use App\Domain\Price\ValueObject\Price as PriceDTO;
use App\Domain\Price\Exception\PriceModuleDynamicUpdateParametersException;
use App\Domain\Price\PriceDynamicModuleInterface;
use App\Domain\Price\PriceModule;
use App\Domain\Price\PriceModuleDynamicTrait;
use App\Domain\Price\PriceModuleTrait;
use Doctrine\ORM\Mapping as ORM;
use PriceDataService;
use DateTime;

/**
 * @ORM\Entity
 */
class DemandDaily extends PriceModule implements PriceDynamicModuleInterface
{
    use PriceModuleTrait;
    use PriceModuleDynamicTrait;

    /** @var string */
    private $title = 'Daily Occupancy';

    /** @var string */
    private $description = 'Checks how many times have been booked the same day.';

    /** @var int */
    private $order = 10;

    /** @var bool */
    private $active = true;

    /** @var array */
    private $settings = [
        'workday_demand_daily' => 10000,
        'weekend_demand_daily' => 10000,
    ];

    /** @var array */
    private $settingsRules = [
        'workday_demand_daily' => 'required|integer|min:0|max:999999',
        'weekend_demand_daily' => 'required|integer|min:0|max:999999',
    ];

    /** @var array */
    private $calculationParameters = [];

    public function calculate(PriceDTO $priceDto, DateTime $priceDateTime): void
    {
        if (true === empty($this->calculationParameters)) {
            $this->calculationParameters($priceDto->dateTime);
        }

        //Note:: To make supply more aggressive we are going to use demand curve with supply.
        if (true === $this->isWeekend()) {
            $demandHistoricalSetting = $priceDto->operations['DemandHistorical']['settings']['weekend_demand_historical'];
            $priceDailyValue = $this->settings['weekend_demand_daily'];
        } else {
            $demandHistoricalSetting = $priceDto->operations['DemandHistorical']['settings']['workday_demand_historical'];
            $priceDailyValue = $this->settings['workday_demand_daily'];
        }

        if (0 === $priceDailyValue) {
            $supplyValue = 0;
            $price = 0;
        } else {
            $supplyValue = $this->calculationParameters[$priceDto->dateTime->getTimestamp()];

            $demandHistoricalLeadValue = $priceDto->operations['DemandHistorical']['lead_value'];
            $basePrice = $priceDto->operations['BasePrice']['contribution'];
            $demandValue = ($demandHistoricalLeadValue * $demandHistoricalSetting + $basePrice) / ($demandHistoricalSetting + $basePrice);

            $price = $demandValue * $supplyValue * $priceDailyValue;
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'supply_value' => $supplyValue,
            'contribution' => $price,
        ];

        $priceDto->price += $price;

        $priceDto->operations['DemandDaily'] = $operation;
    }

    /**
     * TODO: Refactor; This interface break ISP(Interface Segregation Principle)
     * The client should not implement an interface that it doesn't use
     *
     * @see App\Domain\Price\PriceDynamicModuleInterface class
     */
    public function updateParameters(): void
    {
    }

    /**
     * TODO: Refactor; Domain layer shouldn't know anything about Application layer (PriceDataService)
     *
     * @throws PriceModuleDynamicUpdateParametersException
     */
    protected function calculationParameters($dateTime): void
    {
        /*try {
            $calculationParameters = PriceDataService::getGolfCourseDemandDailyCalculationParameters($this->getPricePeriod()->getGolfCourse(), $dateTime);
            $this->calculationParameters = $calculationParameters;
        } catch (PriceDataServiceException $e) {
            throw new PriceModuleDynamicUpdateParametersException($e->getMessage());
        }*/
    }
}
