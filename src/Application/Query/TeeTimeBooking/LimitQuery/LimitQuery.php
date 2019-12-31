<?php

declare(strict_types=1);

namespace App\Application\Query\TeeTimeBooking\LimitQuery;

use App\Domain\Promotion\Membership;
use App\Domain\Promotion\Component\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Player\Player;
use App\Domain\Course\Course;

class LimitQuery
{
    /** @var Player */
    public $player;

    /** @var Course */
    public $course;

    /** @var Membership */
    public $membership;

    public function __construct(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion)
    {
        $this->player = $promotionSubject->getOwner();
        $this->course = $promotionSubject->getCourse();
        $this->membership = $promotion->getMembership();
    }
}
