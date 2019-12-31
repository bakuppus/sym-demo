<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Payment\Core\CreditCardInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Payment implements PaymentInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const GRAPH = 'payment';

    public const TRANSITION_CREATE = 'create';
    public const TRANSITION_PROCESS = 'process';
    public const TRANSITION_COMPLETE = 'complete';
    public const TRANSITION_FAIL = 'fail';
    public const TRANSITION_REFUND = 'refund';

    public const STATE_INIT = 'init';
    public const STATE_NEW = 'new';
    public const STATE_PROCESSING = 'processing';
    public const STATE_COMPLETED = 'completed';
    public const STATE_REFUNDED = 'refunded';
    public const STATE_FAILED = 'failed';

    public const CURRENCY_SEK = 'SEK';

    /**
     * @var OrderInterface
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Order\Order",
     *     inversedBy="payments",
     *     cascade={"persist"}
     * )
     */
    private $order;

    /**
     * @var PaymentMethodInterface|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Payment\PaymentMethod",
     *     inversedBy="payments"
     * )
     */
    private $method;

    /**
     * @var CreditCardInterface|null
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Payment\CreditCard",
     *     inversedBy="payments"
     * )
     */
    private $creditCard;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    private $currencyCode;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups("pay_for_order")
     */
    private $state = self::STATE_INIT;

    /**
     * @var array|null
     *
     * @ORM\Column(type="array", nullable=true)
     *
     * @Groups("pay_for_order")
     */
    private $details;

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getPaymentMethod(): ?PaymentMethodInterface
    {
        return $this->method;
    }

    public function setPaymentMethod(?PaymentMethodInterface $method): void
    {
        $this->method = $method;
    }

    public function getCreditCard(): ?CreditCardInterface
    {
        return $this->creditCard;
    }

    public function setCreditCard(?CreditCardInterface $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(?array $details): void
    {
        $this->details = $details;
    }
}
