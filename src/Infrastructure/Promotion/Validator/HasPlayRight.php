<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HasPlayRight extends Constraint
{
    /** @var string */
    public $message = 'The player should have play right in this club';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
