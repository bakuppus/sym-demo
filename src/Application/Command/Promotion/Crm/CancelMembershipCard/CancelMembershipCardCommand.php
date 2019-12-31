<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\CancelMembershipCard;

use App\Domain\Promotion\MembershipCard;
use App\Infrastructure\Promotion\Validator\IsManuallyPaid;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use App\Infrastructure\Shared\Command\CommandPopulatableTrait;

/** @IsManuallyPaid(groups={"cancel_membership_card"}) */
final class CancelMembershipCardCommand implements CommandAwareInterface, CommandPopulatableInterface
{
    use CommandPopulatableTrait;

    /**
     * @return MembershipCard|object
     */
    public function getResource(): object
    {
        return $this->getObjectToPopulate();
    }

    /**
     * @return MembershipCard|object
     */
    public function getObjectToPopulate(): object
    {
        return $this->objectToPopulate;
    }
}
