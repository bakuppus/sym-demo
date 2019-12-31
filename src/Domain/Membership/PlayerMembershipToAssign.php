<?php

declare(strict_types=1);

namespace App\Domain\Membership;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Domain\Player\Player;
use App\Domain\Club\Club;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class PlayerMembershipToAssign
 *
 * @package App\DAO\Entities
 *
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_index", columns={"player_id", "golf_club_id", "membership_name"})})
 */
class PlayerMembershipToAssign
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Player\Player", cascade={"persist"}, inversedBy="playerMembershipToAssign")
     */
    protected $player;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", cascade={"persist"}, inversedBy="playerMembershipToAssign")
     */
    protected $golfClub;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Groups({"player_list"})
     */
    protected $membershipName;

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getGolfClub(): Club
    {
        return $this->golfClub;
    }

    public function setGolfClub(Club $golfClub): self
    {
        $this->golfClub = $golfClub;

        return $this;
    }

    public function getMembershipName(): string
    {
        return $this->membershipName;
    }

    public function setMembershipName(?string $membershipName): self
    {
        $this->membershipName = $membershipName;

        return $this;
    }
}
