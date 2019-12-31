<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateMembership;

use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @Groups({"update_membership"})
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    public $name;

    /**
     * @var array
     *
     * @Groups({"update_membership"})
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
     * @Groups({"update_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isHidden;

    /**
     * @var bool
     *
     * @Groups({"update_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isGitSync;

    /**
     * @var bool
     *
     * @Groups({"update_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isActive;

    /**
     * @var bool
     *
     * @Groups({"update_membership"})
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $playRightOnly;

    /**
     * @var Membership
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return Membership|object
     */
    public function getResource(): object
    {
        $resource = $this->getObjectToPopulate();
        $resource->setName($this->name);
        $resource->setDurationOptions($this->durationOptions);
        $resource->setIsHidden($this->isHidden);
        $resource->setPlayRightOnly($this->playRightOnly);
        $resource->setIsGitSync($this->isGitSync);
        $resource->setIsActive($this->isActive);

        return $resource;
    }

    /**
     * @return Membership|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
