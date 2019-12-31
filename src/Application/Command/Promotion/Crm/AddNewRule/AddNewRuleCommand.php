<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewRule;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\PromotionRule;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Infrastructure\Promotion\Validator\IsValidRuleInput;

/**
 * @IsValidRuleInput()
 */
final class AddNewRuleCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @var string
     *
     * @Groups({"add_new_rule"})
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    public $type;

    /**
     * @var array
     *
     * @Groups({"add_new_rule"})
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
     * @return object|PromotionRule
     */
    public function getResource(): object
    {
        $resource = new PromotionRule();
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