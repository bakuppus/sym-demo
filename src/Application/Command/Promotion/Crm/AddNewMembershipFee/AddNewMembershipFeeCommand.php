<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewMembershipFee;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Accounting\FeeUnitInterface;
use App\Domain\Promotion\Membership;
use App\Domain\Promotion\MembershipFee;
use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use App\Infrastructure\Shared\Validator\UniqueCommand;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueCommand(
 *     targetEntity="App\Domain\Promotion\MembershipFee",
 *     message="Fee is already added to membership.",
 *     uniqueFields={"feeUnit", "membership"},
 *     groups={"create_fee"}
 * )
 */
final class AddNewMembershipFeeCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var int|FeeUnitInterface
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"create_fee"})
     * @Assert\NotBlank(groups={"create_fee"})
     * @Assert\Type("integer", groups={"create_fee"})
     * @CommandBind(targetEntity="App\Domain\Accounting\FeeUnit")
     */
    public $feeUnit;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"create_fee"})
     * @Assert\NotBlank(groups={"create_fee"})
     * @Assert\Type("integer", groups={"create_fee"})
     * @Assert\GreaterThanOrEqual(0, groups={"create_fee"})
     * @Assert\LessThanOrEqual(100, groups={"create_fee"})
     * @Assert\NotNull(groups={"create_fee"})
     */
    public $vat;

    /**
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="int",
     *             "example"=1
     *         }
     *     }
     * )
     *
     * @Groups({"create_fee"})
     * @Assert\NotBlank(groups={"create_fee"})
     * @Assert\Type("integer", groups={"create_fee"})
     * @Assert\GreaterThanOrEqual(0, groups={"create_fee"})
     */
    public $price;

    /**
     * @var Membership
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return MembershipFee|object
     */
    public function getResource(): object
    {
        $resource = new MembershipFee();
        $resource->setMembership($this->getObjectToPopulate());
        $resource->setFeeUnit($this->feeUnit);
        $resource->setVat($this->vat);
        $resource->setPrice($this->price);

        return $resource;
    }

    /**
     * @return Membership|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
