<?php

declare(strict_types=1);

namespace App\Domain\ApplicationVersion;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="FK_J3F4THWGTSUK1KZI", columns={"platform"})})
 */
class ApplicationVersion
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    public const PLATFORM_ANDROID = 'android';
    public const PLATFORM_IOS = 'ios';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    private $platform;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     *
     * @return self
     */
    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }
}
