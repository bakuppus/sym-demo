<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Core;

use App\Domain\Club\Component\ClubAwareInterface;
use App\Domain\Order\Core\OrderAwareInterface;
use App\Domain\Player\Component\PlayerAwareInterface;
use App\Domain\Promotion\Component\MembershipCardInterface as BaseMembershipCardInterface;
use App\Domain\Promotion\Component\MembershipInterface;
use App\Domain\Promotion\Core\MembershipInterface as CoreMembershipInterface;
use App\Infrastructure\Shared\Command\DeleteCommandAwareInterface;
use App\Infrastructure\Shared\Command\DeleteCommandWorkflowInterface;

interface MembershipCardInterface extends BaseMembershipCardInterface,
    ClubAwareInterface,
    PlayerAwareInterface,
    DeleteCommandAwareInterface,
    DeleteCommandWorkflowInterface,
    OrderAwareInterface
{
    /**
     * @return MembershipInterface|CoreMembershipInterface|null
     */
    public function getMembership(): ?MembershipInterface;

    /**
     * @param MembershipInterface|CoreMembershipInterface|null $membership
     *
     * @return BaseMembershipCardInterface|CoreMembershipInterface
     */
    public function setMembership(?MembershipInterface $membership): BaseMembershipCardInterface;
}
