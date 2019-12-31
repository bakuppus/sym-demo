<?php

declare(strict_types=1);

namespace App\Domain\Price\ValueObject;

/*use App\Core\Domain\DTO\DtoAwareInterface;
use JMS\Serializer\Annotation as JMS;*/
use DateTime;

class Price/* implements DtoAwareInterface*/
{
//    /**
//     * @var DateTime
//     *
//     * @JMS\Type("DateTime")
//     * @JMS\SerializedName("date_time")
//     */
    public $dateTime;

//    /**
//     * @var array
//     *
//     * @JMS\Type("array")
//     * @JMS\SerializedName("operations")
//     */
    public $operations;

//    /**
//     * @var int
//     *
//     * @JMS\Type("int")
//     * @JMS\SerializedName("price")
//     */
    public $price;

//    /**
//     * @var string
//     *
//     * @JMS\Type("string")
//     * @JMS\SerializedName("currency")
//     */
    public $currency;

//    /**
//     * @var bool
//     *
//     * @JMS\Type("bool")
//     * @JMS\SerializedName("override")
//     */
    public $override;

//    /**
//     * @var float
//     *
//     * @JMS\Type("float")
//     * @JMS\SerializedName("debug")
//     */
    public $debug;

//    /**
//     * @var int
//     */
    public $discountPrice;
}
