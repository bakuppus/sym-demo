<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateRule;

use App\Domain\Promotion\PromotionRule;
use App\Infrastructure\Promotion\Validator\IsValidRuleInput;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @IsValidRuleInput()
 */
class UpdateRuleCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var array
     *
     * @Groups({"update_rule"})
     * @Assert\Type("array")
     * @Assert\NotBlank
     */
    public $configuration;

    /**
     * @var PromotionRule
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
     * @return object|PromotionRule
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}