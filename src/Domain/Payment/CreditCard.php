<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Payment\Core\CreditCardInterface;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Player\Player;
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
class CreditCard implements CreditCardInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var PaymentInterface[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Payment\Payment",
     *     mappedBy="creditCard"
     * )
     */
    private $payments;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Domain\Player\Player",
     *     inversedBy="creditCards"
     * )
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=4)
     */
    private $lastFour;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function getCustomer(): Player
    {
        return $this->customer;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand)
    {
        $this->brand = $brand;
    }

    public function getLastFour(): string
    {
        return $this->lastFour;
    }

    public function setLastFour(string $lastFour)
    {
        $this->lastFour = $lastFour;
    }
}