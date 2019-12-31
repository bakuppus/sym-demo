<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateMembershipFee;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Domain\Accounting\FeeInterface;
use App\Domain\Promotion\MembershipFee;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateMembershipFeeCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

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
     * @Groups({"update_fee"})
     * @Assert\NotBlank(groups={"update_fee"})
     * @Assert\Type("integer", groups={"update_fee"})
     * @Assert\GreaterThanOrEqual(0, groups={"update_fee"})
     * @Assert\LessThanOrEqual(100, groups={"update_fee"})
     * @Assert\NotNull(groups={"update_fee"})
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
     * @Groups({"update_fee"})
     * @Assert\NotBlank(groups={"update_fee"})
     * @Assert\Type("integer", groups={"update_fee"})
     * @Assert\GreaterThanOrEqual(0, groups={"update_fee"})
     */
    public $price;

    /**
     * @var MembershipFee
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return FeeInterface|object
     */
    public function getResource(): object
    {
        $resource = $this->getObjectToPopulate();
        $resource->setVat($this->vat);
        $resource->setPrice($this->price);

        return $resource;
    }

    /**
     * @return FeeInterface|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
