<?php

declare(strict_types=1);

namespace App\Domain\Accounting;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Application\Command\Promotion\Crm\UpdateMembershipFee\UpdateMembershipFeeCommand;

/**
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={
 *          "get"={
 *              "path"="/crm/fees",
 *              "normalization_context"={"groups"={"Default", "list_fees"}},
 *              "denormalization_context"={"groups"={"Default", "list_fees"}},
 *              "swagger_context"={
 *                  "summary"="Get all fees",
 *              }
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/crm/fees/{id}",
 *              "normalization_context"={"groups"={"Default", "get_fee"}},
 *              "denormalization_context"={"groups"={"Default", "get_fee"}},
 *              "swagger_context"={
 *                  "summary"="Get fee resource",
 *              }
 *          },
 *          "put"={
 *              "path"="/crm/fees/{id}",
 *              "input"=UpdateMembershipFeeCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_fee"}},
 *              "denormalization_context"={"groups"={"Default", "update_fee"}},
 *              "swagger_context"={
 *                  "summary"="Update membership fee",
 *              },
 *              "validation_groups"={"Default", "update_fee"}
 *          },
 *          "delete"={"path"="/crm/fees/{id}"},
 *     }
 * )
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="FK_WRHUZNAM51SPDF30", columns={"fee_unit_id", "membership_id"})
 * })
 */
abstract class Fee implements FeeInterface, DeleteCommandAwareInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var FeeUnitInterface|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Accounting\FeeUnit")
     *
     * @Groups({"create_fee", "get_fee", "update_fee", "list_memberships", "list_fees"})
     */
    private $feeUnit;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default": 0})
     *
     * @Groups({"create_fee", "get_fee", "update_fee", "list_memberships", "list_fees"})
     */
    private $vat;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default": 0})
     *
     * @Groups({"create_fee", "get_fee", "update_fee", "list_memberships", "list_fees"})
     */
    private $price;

    public function getFeeUnit(): ?FeeUnitInterface
    {
        return $this->feeUnit;
    }

    public function setFeeUnit(?FeeUnitInterface $feeUnit): FeeInterface
    {
        $this->feeUnit = $feeUnit;

        return $this;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(?int $vat): FeeInterface
    {
        $this->vat = $vat;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): FeeInterface
    {
        $this->price = $price;

        return $this;
    }
}
