<?php

declare(strict_types=1);

namespace App\Domain\Order;

use App\Domain\Club\Club;
use App\Domain\Course\Course;
use App\Domain\Order\Component\OrderItemInterface;
use App\Domain\Order\Core\OrderInterface;
use App\Domain\Order\Item\OrderItem;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Payment;
use App\Domain\Player\Player;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Infrastructure\Shared\Command\DeleteCommandWorkflowInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *     "order" = "Order",
 *     "order_booking" = "OrderBooking",
 *     "order_membership" = "OrderMembership"
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Order implements OrderInterface, DeleteCommandAwareInterface, DeleteCommandWorkflowInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const GRAPH = 'order';
    public const PAYMENT_GRAPH = 'order_payment';

    public const STATE_INIT = 'init';
    public const STATE_NEW = 'new';
    public const STATE_CANCELLED = 'cancelled';
    public const STATE_FULFILLED = 'fulfilled';
    public const STATE_DELETED = 'deleted';

    public const TRANSITION_CREATE = 'create';
    public const TRANSITION_CANCEL = 'cancel';
    public const TRANSITION_FULFILL = 'fulfill';
    public const TRANSITION_REMOVE = 'remove';

    public const PAYMENT_STATE_NEW = 'new';
    public const PAYMENT_STATE_AWAITING_PAYMENT = 'awaiting_payment';
    public const PAYMENT_STATE_PARTIALLY_PAID = 'partially_paid';
    public const PAYMENT_STATE_PAID = 'paid';
    public const PAYMENT_STATE_CANCELLED = 'cancelled';
    public const PAYMENT_STATE_PARTIALLY_REFUNDED = 'partially_refunded';
    public const PAYMENT_STATE_REFUNDED = 'refunded';

    public const PAYMENT_TRANSITION_REQUEST_PAYMENT = 'request_payment';
    public const PAYMENT_TRANSITION_PAY = 'pay';
    public const PAYMENT_TRANSITION_REFUND = 'refund';

    /**
     * @var Club
     *
     * @Groups({"get_order"})
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club")
     * @ORM\JoinColumn(nullable=false)
     */
    private $club;

    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Course\Course")
     * @ORM\JoinColumn(nullable=true)
     */
    private $course;

    /**
     * @var Player|null
     *
     * @Groups({"get_order"})
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="orders")
     */
    private $customer;

    /**
     * @var OrderItem[]|Collection
     *
     * @Groups({"get_order"})
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Order\Item\OrderItem",
     *     mappedBy="order",
     *     orphanRemoval=true,
     *     cascade={"all"}
     * )
     */
    private $items;

    /**
     * @var string|null
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @var string|null
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @var string
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $state = self::STATE_INIT;

    /**
     * @var int
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="integer")
     */
    private $itemsTotal;

    /**
     * @var int
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @var string
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=3)
     */
    private $currencyCode;

    /**
     * @var string
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $localeCode;

    /**
     * @var string
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $paymentState = self::PAYMENT_STATE_AWAITING_PAYMENT;

    /**
     * @var string|null
     *
     * @Groups({"get_order", "get_membership_card", "add_card_to_membership"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var Payment[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Payment\Payment",
     *     mappedBy="order",
     *     orphanRemoval=true,
     *     cascade={"all"}
     * )
     */
    private $payments;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getClub(): Club
    {
        return $this->club;
    }

    public function setClub(Club $club): void
    {
        $this->club = $club;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    public function getCustomer(): ?Player
    {
        return $this->customer;
    }

    public function setCustomer(?Player $customer): void
    {
        $this->customer = $customer;
    }

    /** @inheritDoc */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItemInterface $item): void
    {
        if (true === $this->hasItem($item)) {
            return;
        }

        $this->itemsTotal += $item->getTotal();
        $this->items->add($item);
        $item->setOrder($this);

        $this->recalculateTotal();
    }

    public function removeItem(OrderItemInterface $item): void
    {
        if (false === $this->hasItem($item)) {
            return;
        }

        $this->items->removeElement($item);
        $this->itemsTotal -= $item->getTotal();
        $this->recalculateTotal();
        $item->setOrder(null);
    }

    public function hasItem(OrderItemInterface $item): bool
    {
        return $this->items->contains($item);
    }

    public function countItems(): int
    {
        return $this->items->count();
    }

    public function clearItems(): void
    {
        $this->items->clear();

        $this->recalculateItemsTotal();
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(int $itemsTotal): void
    {
        $this->itemsTotal = $itemsTotal;
    }

    public function recalculateItemsTotal(): void
    {
        $this->itemsTotal = 0;
        foreach ($this->items as $item) {
            $this->itemsTotal += $item->getTotal();
        }

        $this->recalculateTotal();
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getTotalQuantity(): int
    {
        $quantity = 0;

        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }

    public function getPaymentState(): string
    {
        return $this->paymentState;
    }

    public function setPaymentState(string $paymentState): void
    {
        $this->paymentState = $paymentState;
    }

    protected function recalculateTotal(): void
    {
        /*$this->total = $this->itemsTotal + $this->adjustmentsTotal;*/
        $this->total = $this->itemsTotal;

        if ($this->total < 0) {
            $this->total = 0;
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getWorkflow(): string
    {
        return self::GRAPH;
    }

    public function removeTransitionName(): string
    {
        return self::TRANSITION_REMOVE;
    }

    /**
     * @return Collection|PaymentInterface[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(PaymentInterface $payment): void
    {
        if (true === $this->hasPayment($payment)) {
            return;
        }

        $this->payments->add($payment);
        $payment->setOrder($this);
    }

    public function hasPayment(PaymentInterface $payment): bool
    {
        return $this->payments->contains($payment);
    }
}
