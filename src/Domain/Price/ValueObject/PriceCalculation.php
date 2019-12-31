<?php

declare(strict_types=1);

namespace App\Domain\Price\ValueObject;

/*use App\Core\Domain\DTO\DtoAwareInterface;
use JMS\Serializer\Annotation as JMS;*/
use DateTime;

class PriceCalculation/* implements DtoAwareInterface*/
{
//    /**
//     * @var array
//     *
//     * @JMS\Type("ArrayCollection<App\Core\Services\PriceModule\Modules\DTO\Price>")
//     * @JMS\SerializedName("prices")
//     */
    public $prices;

//    /**
//     * @var DateTime
//     *
//     * @JMS\Type("DateTime")
//     * @JMS\SerializedName("date_time")
//     */
    public $dateTime;

//    /**
//     * @var float
//     *
//     * @JMS\Type("float")
//     * @JMS\SerializedName("debug")
//     */
    public $debug;
}
