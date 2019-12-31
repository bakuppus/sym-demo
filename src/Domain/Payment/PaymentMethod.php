<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Core\GatewayConfigInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PaymentMethod implements PaymentMethodInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const ENVIRONMENT_SANDBOX = 'sandbox';
    public const ENVIRONMENT_PRODUCTION = 'production';

    public const CODE_CARD = 'card';
    public const CODE_ON_SITE = 'on_site';

    /**
     * @var GatewayConfigInterface
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Payment\GatewayConfig",
     *     inversedBy="paymentMethods"
     * )
     */
    private $gatewayConfig;

    /**
     * @var PaymentInterface[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Payment\Payment",
     *     mappedBy="method"
     * )
     */
    private $payments;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $environment;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isEnabled = false;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getGatewayConfig(): GatewayConfigInterface
    {
        return $this->gatewayConfig;
    }

    public function setGatewayConfig(GatewayConfigInterface $gatewayConfig): void
    {
        $this->gatewayConfig = $gatewayConfig;
    }

    public function addPayment(PaymentInterface $payment): void
    {
        if (true === $this->payments->contains($payment)) {
            return;
        }

        $this->payments->add($payment);
        $payment->setPaymentMethod($this);
    }

    public function getCode(): string
    {
        return $this->getCode();
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function setEnvironment(?string $environment): void
    {
        $this->environment = $environment;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }
}