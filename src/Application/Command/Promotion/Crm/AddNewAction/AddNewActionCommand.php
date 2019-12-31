<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewAction;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\PromotionAction;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Promotion\Validator\IsValidActionInput;

/**
 * @IsValidActionInput()
 */
class AddNewActionCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @Groups({"add_new_action"})
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    public $type;

    /**
     * @Groups({"add_new_action"})
     * @Assert\Type("array")
     * @Assert\NotBlank
     */
    public $configuration;

    /**
     * @var PromotionInterface
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return object|PromotionAction
     */
    public function getResource(): object
    {
        $resource = new PromotionAction();
        $resource->setType($this->type);
        $resource->setConfiguration($this->configuration);
        $resource->setPromotion($this->getObjectToPopulate());

        return $resource;
    }

    /**
     * @return object|PromotionInterface
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}