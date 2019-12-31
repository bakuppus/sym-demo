<?php

declare(strict_types=1);

namespace App\Domain\Accounting;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"Default", "list_fees"}}
 * )
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class FeeUnit implements FeeUnitInterface
{
    use AutoTrait;
    use UuidTrait;
    use TimestampableEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"list_fees", "get_fee", "create_fee", "update_fee", "list_memberships"})
     */
    protected $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): FeeUnitInterface
    {
        $this->name = $name;

        return $this;
    }
}
