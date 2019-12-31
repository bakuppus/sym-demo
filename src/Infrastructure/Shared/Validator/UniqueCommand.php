<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCommand extends Constraint
{
    public $targetEntity;
    public $uniqueFields;
    public $message = 'This value is already used.';
    public $errorPath = null;

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
