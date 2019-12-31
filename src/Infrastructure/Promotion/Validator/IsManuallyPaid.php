<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsManuallyPaid extends Constraint
{
    /** @var string */
    public $message = 'You can cancel only manually paid membership';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
