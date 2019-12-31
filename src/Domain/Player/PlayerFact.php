<?php

declare(strict_types=1);

namespace App\Domain\Player;

use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class PlayerFact
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", inversedBy="facts")
     */
    private $player;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="facts")
     * @ORM\JoinColumn(name="golf_club_id")
     *
     * @Groups({"player_list"})
     */
    private $club;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, options={"default":0})
     *
     * @Groups({"player_list"})
     */
    private $playValue = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default":0})
     *
     * @Groups({"player_list"})
     */
    private $numberOfRounds = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=6, length=20, options={"default":0})
     *
     * @Groups({"player_list"})
     */
    private $paidValue = 0;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"player_list"})
     */
    protected $lastPlayed;

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

    public function getClub(): Club
    {
        return $this->club;
    }

    public function setClub(Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getPlayValue(): float
    {
        return $this->playValue;
    }

    public function setPlayValue(float $playValue): self
    {
        $this->playValue = $playValue;

        return $this;
    }

    public function getNumberOfRounds(): int
    {
        return $this->numberOfRounds;
    }

    public function setNumberOfRounds(int $numberOfRounds): self
    {
        $this->numberOfRounds = $numberOfRounds;

        return $this;
    }

    public function getPaidValue(): float
    {
        return $this->paidValue;
    }

    public function setPaidValue(float $paidValue): self
    {
        $this->paidValue = $paidValue;

        return $this;
    }

    public function getLastPlayed(): ?DateTime
    {
        return $this->lastPlayed;
    }

    public function setLastPlayed(?DateTime $lastPlayed): void
    {
        $this->lastPlayed = $lastPlayed;
    }
}
