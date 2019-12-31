<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Domain\Club\Club;
use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class PlayRightImport
 *
 * @package App\DAO\Entities
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class PlayRightImport
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    public const STATUS_IN_PROGRESS = 'in progress';
    public const STATUS_PROCESSED = 'processed';

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="playRightImports", fetch="EAGER")
     */
    protected $golfClub;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    protected $golfIds;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    protected $failedGolfIds;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @return Club
     */
    public function getGolfClub(): Club
    {
        return $this->golfClub;
    }

    /**
     * @param Club $golfClub
     *
     * @return $this
     */
    public function setGolfClub(Club $golfClub): self
    {
        $this->golfClub = $golfClub;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getGolfIds(): ?array
    {
        return $this->golfIds;
    }

    /**
     * @param array|null $golfIds
     *
     * @return $this
     */
    public function setGolfIds(?array $golfIds): self
    {
        $this->golfIds = $golfIds;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFailedGolfIds(): ?array
    {
        return $this->failedGolfIds;
    }

    /**
     * @param array|null $failedGolfIds
     *
     * @return $this
     */
    public function setFailedGolfIds(?array $failedGolfIds): self
    {
        $this->failedGolfIds = $failedGolfIds;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
