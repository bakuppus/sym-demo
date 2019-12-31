<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *      @ORM\UniqueConstraint(name="FK_XBF7FLGOOZ0Q", columns={"player_id", "token"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class PlayerMobileDevice
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    public const PLATFORM_ANDROID = 'android';
    public const PLATFORM_IOS = 'ios';
    public const PLATFORMS = [
        self::PLATFORM_ANDROID,
        self::PLATFORM_IOS,
    ];

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="mobileDevices", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=550)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    private $language;/* TODO: = UserInterface::LANG_SWEDISH;*/

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $arn;

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getArn(): string
    {
        return $this->arn;
    }

    public function setArn(string $arn): self
    {
        $this->arn = $arn;

        return $this;
    }
}
