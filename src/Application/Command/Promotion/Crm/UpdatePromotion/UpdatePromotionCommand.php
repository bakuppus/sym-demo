<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdatePromotion;

use App\Domain\Promotion\Membership;
use App\Domain\Promotion\Promotion;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdatePromotionCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @Groups({"edit_promotion"})
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @var Promotion
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return Promotion|object
     */
    public function getResource(): object
    {
        $resource = $this->getObjectToPopulate();
        $resource->setName($this->name);

        return $resource;
    }

    /**
     * @return Promotion|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}