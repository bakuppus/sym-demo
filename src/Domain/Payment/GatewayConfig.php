<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Core\GatewayConfigInterface;
use App\Domain\Payment\Core\PaymentMethodInterface;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Payum\Core\Model\GatewayConfig as BaseGatewayConfig;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UQ_OVB1G2S67YVD5TYY", columns={"gateway_name", "factory_name"})
 * })
 */
class GatewayConfig extends BaseGatewayConfig implements GatewayConfigInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const GATEWAY_NAME_OFFLINE = 'offline';
    public const FACTORY_NAME_OFFLINE = 'offline';

    /**
     * @var PaymentMethodInterface[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Payment\PaymentMethod",
     *     mappedBy="gatewayConfig"
     * )
     */
    private $paymentMethods;

    public function __construct()
    {
        parent::__construct();

        $this->paymentMethods = new ArrayCollection();
    }

    public function addPaymentMethod(PaymentMethodInterface $paymentMethod): void
    {
        if (true === $this->paymentMethods->contains($paymentMethod)) {
            return;
        }

        $this->paymentMethods->add($paymentMethod);
        $paymentMethod->setGatewayConfig($this);
    }

    /** @inheritDoc */
    public function getPaymentMethods(): Collection
    {
        return $this->paymentMethods;
    }
}