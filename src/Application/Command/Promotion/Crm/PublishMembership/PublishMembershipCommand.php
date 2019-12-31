<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\PublishMembership;

use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;
use App\Infrastructure\Promotion\Validator\CanPublishMembership;

/**
 * todo: change CanPublishMembershipValidator to PublishSateResolver into Handler
 * @CanPublishMembership
 */
final class PublishMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @return Membership|object
     */
    public function getResource(): object
    {
        return $this->getObjectToPopulate();
    }

    /**
     * @return Membership|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}