<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UNIQ_J1A82MVOF7VK3UNJ", columns={"player_id", "friend_id"})
 * })
 */
class PlayerGolfFriend
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="golfFriends")
     */
    private $player;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player")
     */
    private $friend;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $playedAt;

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

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getFriend(): Player
    {
        return $this->friend;
    }

    public function setFriend(Player $friend): self
    {
        $this->friend = $friend;

        return $this;
    }

    public function getPlayedAt(): DateTime
    {
        return $this->playedAt;
    }

    public function setPlayedAt(DateTime $playedAt): self
    {
        $this->playedAt = $playedAt;

        return $this;
    }
}
