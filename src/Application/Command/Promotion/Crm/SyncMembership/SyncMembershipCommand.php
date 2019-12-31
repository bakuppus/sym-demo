<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\SyncMembership;

use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;

final class SyncMembershipCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @return Membership|object
     */
    public function getResource(): object
    {
        return $this->objectToPopulate;
    }

    /**
     * @return Membership|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
