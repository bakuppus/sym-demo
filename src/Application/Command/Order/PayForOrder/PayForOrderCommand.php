<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PayForOrder;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Order\Core\OrderInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Payment\Payment;

/**
 * @ApiResource(
 *   shortName="Payment",
 *   messenger=true,
 *   collectionOperations={},
 *   itemOperations={
 *         "pay_for_order"={
 *              "method"="PUT",
 *              "path"="/orders/{id}/pay",
 *              "output"=Payment::class,
 *              "normalization_context"={"groups"={"Default", "pay_for_order"}},
 *              "denormalization_context"={"groups"={"Default", "pay_for_order"}},
 *              "swagger_context"={
 *                  "summary"="Pay for order by order token",
 *              }
 *          }
 *     }
 * )
 */
final class PayForOrderCommand
{
    /**
     * @var string
     *
     * @ApiProperty(identifier=true)
     *
     * @Assert\Type("string")
     */
    public $token;

    /**
     * @var array
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *              "type"="object",
     *              "example"={
     *                  "paymentMethodNonce"="tokencc_bh_w4g8kk_kwwdr4_yrq6yh_m9vf4r_h64"
     *              }
     *         }
     *     }
     * )
     *
     * @Groups({"pay_for_order"})
     *
     * @Assert\Type("array")
     * @Assert\NotBlank
     */
    public $parameters;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *              "type"="string",
     *              "example"="braintree_card"
     *         }
     *     }
     * )
     *
     * @Groups({"pay_for_order"})
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    public $method;

    /**
     * @var OrderInterface
     *
     * @Assert\Type("object")
     */
    public $order;
}