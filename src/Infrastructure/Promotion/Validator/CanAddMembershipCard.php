<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use ReflectionClass;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CanAddMembershipCard extends Constraint
{
    /** @var string */
    public $messageFutureOrUpcoming = 'There is future or upcoming membership already';

    /** @var string */
    public $messageCurrentOrNextYear = 'Invalid year. You can add membership to either current or next year only';

    /** @var string */
    public $messageInvalidDates = 'Invalid year. A membership is already exist for this year';

    /** @var string */
    public $messageInvalidMembership = 'Player cat\'t be added to inactive or not published membership';

    /** @var string */
    public $membershipField = 'membership';

    /** @var string */
    public $playerField = 'player';

    /** @var string */
    public $durationType = 'durationType';

    /** @var string */
    public $calendarYear = 'calendarYear';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
