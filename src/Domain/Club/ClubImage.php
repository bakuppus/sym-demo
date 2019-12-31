<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="golf_club_images")
 */
class ClubImage
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="images")
     * @ORM\JoinColumn(name="golf_club_id")
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    public function getClub(): Club
    {
        return $this->club;
    }

    public function setClub(Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
