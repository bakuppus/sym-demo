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
use DateTimeZone;
use DateTime;

/**
 * @ORM\Entity
 */
class DemandHistorical extends PriceModule implements PriceDynamicModuleInterface
{
    use PriceModuleTrait;
    use PriceModuleDynamicTrait;

    public const WEEKEND_COEFFICIENT = 1;
    public const WEEKEND_MINIMUM = 2;
    public const WEEKEND_MAXIMUM = 3;
    public const WEEKEND_WHOLE_HOUR = 4;
    public const WEEKEND_HALF_HOUR = 5;

    public const WORKDAY_COEFFICIENT = 6;
    public const WORKDAY_MINIMUM = 7;
    public const WORKDAY_MAXIMUM = 8;
    public const WORKDAY_WHOLE_HOUR = 9;
    public const WORKDAY_HALF_HOUR = 10;

    /** @var string */
    private $title = 'Historical Demand';

    /** @var string */
    private $description = 'Modifies the price with regards to statistical analysis of lead times.';

    /** @var int */
    private $order = 5;

    /** @var bool */
    private $active = true;

    /** @var array */
    private $settings = [
        'workday_demand_historical' => 20000,
        'weekend_demand_historical' => 20000,
    ];

    /** @var array */
    private $settingsRules = [
        'workday_demand_historical' => 'required|integer|min:0|max:999999',
        'weekend_demand_historical' => 'required|integer|min:0|max:999999',
    ];

    /** @var array */
    private $parametersRules = [
        '*.1.1' => 'required|numeric|between:0,100',
        '*.1.2' => 'required|numeric|between:0,100',
        '*.1.3' => 'required|numeric|between:0,100',
        '*.1.4' => 'required|numeric|between:0,100',
        '*.1.5' => 'required|numeric|between:0,100',
        '*.1.6' => 'required|numeric|between:0,100',
        '*.2.1' => 'required|numeric|between:0,100',
        '*.3.1' => 'required|numeric|between:0,100',
        '*.4.1' => 'required|numeric|between:0,100',
        '*.5.1' => 'required|numeric|between:0,100',
        '*.6.1' => 'required|numeric|between:0,100',
        '*.7.1' => 'required|numeric|between:0,100',
        '*.8.1' => 'required|numeric|between:0,100',
        '*.9.1' => 'required|numeric|between:0,100',
        '*.10.1' => 'required|numeric|between:0,100',
    ];

    /**
     * {@inheritDoc}
     * @throws PriceModuleDynamicUpdateParametersException
     */
    public function calculate(PriceDTO $priceDto, DateTime $priceDateTime): void
    {
        if (true === empty($this->parameters)) {
            $this->updateParameters();
        }

        if (true === $this->isWeekend()) {
            $this->calculateWeekend($priceDto);
        } else {
            $this->calculateWorkday($priceDto);
        }
    }

    private function calculateWeekend(PriceDTO $priceDto): void
    {
        // TODO: Refactor duplicates on lines 101-111; 133-143
        $month = $priceDto->dateTime->format('n');
        $parameter = $this->parameters[--$month];

        $priceLead = $this->calculateLeadFunctionValue(
            $this->calculateDecimalHoursFromMidnight($priceDto->dateTime),
            $parameter[self::WEEKEND_COEFFICIENT],
            $parameter[self::WEEKEND_MINIMUM][1],
            $parameter[self::WEEKEND_MAXIMUM][1],
            $parameter[self::WEEKEND_WHOLE_HOUR][1],
            $parameter[self::WEEKEND_HALF_HOUR][1]
        );

        if (0 === $this->settings['weekend_demand_historical']) {
            $price = 0;
        } else {
            $price = $priceLead * $this->settings['weekend_demand_historical'];
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'lead_value' => $priceLead,
            'contribution' => $price,
        ];

        $priceDto->price += $price;

        $priceDto->operations['DemandHistorical'] = $operation;
    }

    private function calculateWorkday(PriceDTO $priceDto): void
    {
        // TODO: Refactor duplicates on lines 101-111; 133-143
        $month = $priceDto->dateTime->format('n');
        $parameter = $this->parameters[--$month];

        $priceLead = $this->calculateLeadFunctionValue(
            $this->calculateDecimalHoursFromMidnight($priceDto->dateTime),
            $parameter[self::WORKDAY_COEFFICIENT],
            $parameter[self::WORKDAY_MINIMUM][1],
            $parameter[self::WORKDAY_MAXIMUM][1],
            $parameter[self::WORKDAY_WHOLE_HOUR][1],
            $parameter[self::WORKDAY_HALF_HOUR][1]
        );

        if (0 === $this->settings['workday_demand_historical']) {
            $price = 0;
        } else {
            $price = $priceLead * $this->settings['workday_demand_historical'];
        }

        $operation = [
            'weekend' => $this->isWeekend(),
            'settings' => $this->settings,
            'lead_value' => $priceLead,
            'contribution' => $price,
        ];

        $priceDto->price += $price;

        $priceDto->operations['DemandHistorical'] = $operation;
    }

    private function calculateLeadFunctionValue(
        float $hours,
        array $coefficients,
        float $data_min,
        float $data_max,
        float $whole_hour_increase,
        float $half_hour_increase
    ): float {

        $s = 0;

        $coefficientsCount = count($coefficients);

        for ($i = 1; $i < $coefficientsCount; $i += 3) {
            $s += $coefficients[$i] * exp(-$coefficients[$i + 1] * (($hours - $coefficients[$i + 2])**2));
        }

        $minutes = $hours - floor($hours);

        if ($minutes < .01 || $minutes > .99) {
            $s = ($s + $data_min) * (1 + $whole_hour_increase) - $data_min;
        }
        if (abs($minutes - .5) < .01) {
            $s = ($s + $data_min) * (1 + $half_hour_increase) - $data_min;
        }
        $s /= ($data_max - $data_min);

        // should be normalized but do it again just to be sure
        return max(0.000001, min(1, $s));
    }

    private function calculateDecimalHoursFromMidnight(DateTime $dateTime): float
    {
        $dateTimeLocal = clone $dateTime;
        //Note: lead_time coefficient is timezone agnostic.
        $offset = $dateTimeLocal->setTimezone(new DateTimeZone(config('sweetspot.timezone')))->getOffset();
        $midnight = clone $dateTime;
        $midnight->setTime(0, 0, 0);

        return (($dateTime->getTimestamp() + $offset) - $midnight->getTimestamp()) / 3600;
    }

    /**
     * TODO: Refactor; Domain layer shouldn't know anything about Application layer (PriceDataService
     */
    public function updateParameters(): void
    {
        /*try {
            PriceDataService::updateGolfCourseDemandHistoryLeadCalculationParameters($this);
        } catch (PriceDataServiceException $e) {
            throw new PriceModuleDynamicUpdateParametersException('Error getting price module parameters');
        }*/
    }
}
