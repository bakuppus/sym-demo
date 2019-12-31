<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use Symfony\Component\Validator\Constraint;

abstract class AbstractConfigurableElementConstraint extends Constraint
{
    /** @var string */
    public $type = 'type';

    /** @var string */
    public $configuration = 'configuration';

    /** @var string */
    public $notValidValueMessage = 'The value %s is not valid.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}