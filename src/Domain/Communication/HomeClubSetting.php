<?php

declare(strict_types=1);

namespace App\Domain\Communication;

use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;

/*use Illuminate\Support\Facades\Storage;*/

/**
 * @ORM\Entity
 */
class HomeClubSetting
{
    use AutoTrait;

    /**
     * @var Club
     *
     * @ORM\OneToOne(targetEntity="App\Domain\Club\Club", inversedBy="homeClubSetting")
     * @ORM\JoinColumn(name="golf_club_id")
     */
    private $club;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=9, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $active = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $backgroundColor;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $clubImage;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

//    TODO: Refactor
//    public function getLogoFullUrl(): ?string
//    {
//        return null === $this->getLogo() ? null : Storage::url($this->getLogo());
//    }

    public function setClubImage(?string $clubImage): self
    {
        $this->clubImage = $clubImage;

        return $this;
    }

    public function getClubImage(): ?string
    {
        return $this->clubImage;
    }

//    TODO: Refactor
//    public function getClubImageFullUrl(): ?string
//    {
//        return null === $this->getClubImage() ? null : Storage::url($this->getClubImage());
//    }
}
