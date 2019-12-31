<?php

declare(strict_types=1);

namespace App\Domain\Promotion;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Promotion\Component\PromotionRuleInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionInterface as BasePromotionInterface;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Application\Command\Promotion\Crm\UpdateRule\UpdateRuleCommand;
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
 *              "path"="/crm/promotions/rule/{id}",
 *              "input"=UpdateRuleCommand::class,
 *              "normalization_context"={"groups"={"Default", "update_rule"}},
 *              "denormalization_context"={"groups"={"Default", "update_rule"}},
 *              "swagger_context"={
 *                  "summary"="Update rule"
 *              },
 *              "validation_groups"={"Default", "edit_membership"}
 *          },
 *          "delete"={"path"="/crm/promotions/rules/{id}"}
 *     }
 * )
 */
class PromotionRule implements PromotionRuleInterface, DeleteCommandAwareInterface
{
    use AutoTrait;
    use UuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"list_memberships", "add_new_rule", "update_rule"})
     */
    private $type = '';

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @Groups({"list_memberships", "add_new_rule", "update_rule"})
     */
    private $configuration = [];

    /**
     * @var PromotionInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Promotion\Promotion", inversedBy="rules")
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

    public function setType(?string $type): PromotionRuleInterface
    {
        $this->type = $type;

        return $this;
    }

    public function setConfiguration(array $configuration): PromotionRuleInterface
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function setPromotion(?BasePromotionInterface $promotion): PromotionRuleInterface
    {
        $this->promotion = $promotion;

        return $this;
    }
}