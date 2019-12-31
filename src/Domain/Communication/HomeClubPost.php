<?php

declare(strict_types=1);

namespace App\Domain\Communication;

use App\Domain\Club\Club;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
class HomeClubPost
{
    use UuidTrait;
    use AutoTrait;
    use TimestampableEntity;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
    ];

    /**
     * @var Club
     *
     * @ORM\ManyToOne(
     *      targetEntity="App\Domain\Club\Club",
     *      inversedBy="homeClubPosts",
     *      fetch="EAGER",
     *      cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="golf_club_id")
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getClub(): Club
    {
        return $this->club;
    }

    public function setClub(Club $club): HomeClubPost
    {
        $this->club = $club;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): HomeClubPost
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): HomeClubPost
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): HomeClubPost
    {
        $this->status = $status;

        return $this;
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTime $publishedAt): HomeClubPost
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
