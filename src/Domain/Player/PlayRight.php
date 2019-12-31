<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PlayRight
 *
 * @package App\DAO\Entities
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class PlayRight
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="playRights", fetch="EAGER")
     */
    protected $player;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="playRights", fetch="EAGER")
     */
    protected $golfClub;

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     *
     * @return $this
     */
    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

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
}
