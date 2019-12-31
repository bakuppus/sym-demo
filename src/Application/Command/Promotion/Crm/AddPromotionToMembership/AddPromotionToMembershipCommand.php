<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddPromotionToMembership;

use App\Domain\Promotion\Membership;
use App\Domain\Promotion\Promotion;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class AddPromotionToMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @Groups({"add_promotion_to_membership"})
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var Membership
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return Promotion|object
     */
    public function getResource(): object
    {
        $resource = new Promotion();
        $resource->setName($this->name);
        $resource->setClub($this->getObjectToPopulate()->getClub());
        $resource->setMembership($this->getObjectToPopulate());

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