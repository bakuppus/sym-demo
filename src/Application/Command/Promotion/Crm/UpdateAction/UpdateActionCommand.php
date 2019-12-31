<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateAction;

use App\Domain\Promotion\Component\PromotionActionInterface;
use App\Infrastructure\Promotion\Validator\IsValidActionInput;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @IsValidActionInput()
 */
final class UpdateActionCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var array
     *
     * @Groups({"update_action"})
     * @Assert\Type("array")
     * @Assert\NotBlank
     */
    public $configuration;

    /**
     * @var PromotionActionInterface
     *
     * @Assert\Valid(groups={"edit_membership"})
     */
    public $objectToPopulate;

    /**
     * @return object
     */
    public function getResource(): object
    {
        $resource = $this->getObjectToPopulate();
        $resource->setConfiguration($this->configuration);

        return $resource;
    }

    /**
     * @return object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
