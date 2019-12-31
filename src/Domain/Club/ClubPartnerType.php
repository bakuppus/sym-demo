<?php

declare(strict_types=1);

namespace App\Domain\Club;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="golf_club_partner_types",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="FK_2DXY8ERI7R03XR4G", columns={"golf_club_id", "name"})
 *      }
 * )
 * @Gedmo\Loggable(logEntryClass="App\Domain\Club\ClubPartnerTypeLogEntry")
 */
class ClubPartnerType
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Club\Club", inversedBy="partnerTypes")
     * @ORM\JoinColumn(name="golf_club_id")
     *
     * @Gedmo\Versioned
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
}
