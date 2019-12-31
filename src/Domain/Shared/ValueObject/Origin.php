<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Origin
{
    public const SOURCE_360 = '360';
    public const SOURCE_APP = 'app';
    public const SOURCE_WIDGET = 'widget';
    public const SOURCE_SYNCED_MIN_GOLF = 'mingolf';
    public const SOURCE_UNKNOWN = 'unknown';

    public const DEVICE_DESKTOP = 'desktop';
    public const DEVICE_TABLET = 'tablet';
    public const DEVICE_PHONE = 'phone';
    public const DEVICE_ROBOT = 'robot';
    public const DEVICE_UNKNOWN = 'unknown';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $deviceType;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $device;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $browser;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $platform;

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function getBrowser(): ?string
    {
        return $this->browser;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setDeviceType(?string $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function setDevice(?string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function setBrowser(?string $browser): self
    {
        $this->browser = $browser;

        return $this;
    }

    public function setPlatform(?string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }
}
