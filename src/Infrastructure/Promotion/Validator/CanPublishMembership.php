<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class CanPublishMembership extends Constraint
{
    public $property = 'state';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}