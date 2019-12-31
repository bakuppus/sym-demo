<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable
 */
class PlayerPaymentMethod
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="paymentMethods", cascade={"persist"})
     */
    private $player;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cardBrand = null;


    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cardLastFour = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $payPalEmail = null;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $isDefault = false;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(?string $token)
    {
        $this->token = $token;

        return $this;
    }

    public function getCardBrand()
    {
        return $this->cardBrand;
    }

    public function setCardBrand(?string $cardBrand)
    {
        $this->cardBrand = $cardBrand;

        return $this;
    }

    public function getCardLastFour()
    {
        return $this->cardLastFour;
    }

    public function setCardLastFour(?string $cardLastFour)
    {
        $this->cardLastFour = $cardLastFour;

        return $this;
    }

    public function getPayPalEmail()
    {
        return $this->payPalEmail;
    }

    public function setPayPalEmail(?string $payPalEmail)
    {
        $this->payPalEmail = $payPalEmail;

        return $this;
    }

    public function isDefault()
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
