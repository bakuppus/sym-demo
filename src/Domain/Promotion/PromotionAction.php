<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Promotion\Component\PromotionActionInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Application\Command\Promotion\Crm\UpdateAction\UpdateActionCommand;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     messenger="input",
 *     collectionOperations={},
 *     itemOperations={
 *          "get",
 *          "put"={
 *              "method"="PUT",
 *              "path"="/crm/promotions/action/{id}",
 *              "input"=UpdateActionCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_action"}},
 *              "denormalization_context"={"groups"={"Default", "update_action"}},
 *              "swagger_context"={
 *                  "summary"="Update action"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "delete"={"path"="/crm/promotions/actions/{id}"}
 *     }
 * )
 */
class PromotionAction implements PromotionActionInterface, DeleteCommandAwareInterface
{
    use AutoTrait;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"list_memberships", "add_new_action", "update_action"})
     */
    private $type = '';

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @Groups({"list_memberships", "add_new_action", "update_action"})
     */
    private $configuration = [];

    /**
     * @var PromotionInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Promotion", inversedBy="actions")
     * @Assert\Valid(groups={"edit_membership"})
     */
    private $promotion;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return BasePromotionInterface|Promotion|null
     */
    public function getPromotion(): ?BasePromotionInterface
    {
        return $this->promotion;
    }

    public function setType(?string $type): PromotionActionInterface
    {
        $this->type = $type;

        return $this;
    }

    public function setConfiguration(array $configuration): PromotionActionInterface
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function setPromotion(?BasePromotionInterface $promotion): PromotionActionInterface
    {
        $this->promotion = $promotion;

        return $this;
    }
}
