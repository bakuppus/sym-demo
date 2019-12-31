<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\AddNewMembership;

use App\Domain\Club\Club;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Infrastructure\Shared\Validator\UniqueCommand;

/**
 * @UniqueCommand(
 *     targetEntity="App\Domain\Promotion\Membership",
 *     uniqueFields={"name", "club"},
 *     message="Membership name must be unique per club.",
 *     groups={"add_new_membership"},
 * )
 */
final class AddNewMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "example"="New Membership"
     *         }
     *     }
     * )
     *
     * @Groups({"add_new_membership"})
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    public $name;

    /**
     * @var array
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="array",
     *             "enum"={
     *                 Membership::DURATION_ANNUAL,
     *                 Membership::DURATION_12_MONTH
     *             },
     *             "example"={Membership::DURATION_ANNUAL}
     *         }
     *     }
     * )
     *
     * @Groups({"add_new_membership"})
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string"),
     *     @Assert\Choice(callback={"App\Domain\Promotion\Membership", "getDurationTypes"})
     * })
     */
    public $durationOptions = [];

    /**
     * @var bool
     *
     * @Groups({"add_new_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isHidden;

    /**
     * @var bool
     *
     * @Groups({"add_new_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isActive;

    /**
     * @var bool
     *
     * @Groups({"add_new_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $playRightOnly;

    /**
     * @return Membership|object
     */
    public function getResource(): object
    {
        $resource = new Membership();
        $resource->setClub($this->getObjectToPopulate());
        $resource->setName($this->name);
        $resource->setDurationOptions($this->durationOptions);
        $resource->setIsHidden($this->isHidden);
        $resource->setIsActive($this->isActive);
        $resource->setPlayRightOnly($this->playRightOnly);

        return $resource;
    }

    /**
     * @return Club|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
