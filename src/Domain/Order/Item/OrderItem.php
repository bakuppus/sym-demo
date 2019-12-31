<?php

declare(strict_types=1);

namespace App\Domain\Order\Item;

use App\Domain\Order\Component\OrderInterface as BaseOrderInterface;
use App\Domain\Order\Core\OrderInterface;
use App\Domain\Order\Core\OrderItemInterface;
use App\Domain\Order\Order;
use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * @DiscriminatorMap({
 *     "order_item" = "OrderItem",
 *     "order_item_membership_card" = "OrderItemMembershipCard"
 * })
 * @ORM\HasLifecycleCallbacks
 */
class OrderItem implements OrderItemInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var Order|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Order\Order", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @var int
     *
     * @Groups({"get_order"})
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @Groups({"get_order"})
     *
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @return BaseOrderInterface|OrderInterface|null
     */
    public function getOrder(): ?BaseOrderInterface
    {
        return $this->order;
    }

    public function setOrder(?BaseOrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
