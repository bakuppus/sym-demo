<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Application\Command\Promotion\Crm\CancelMembershipCard\CancelMembershipCardCommand;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class IsManuallyPaidValidator extends ConstraintValidator
{
    /**
     * @param CancelMembershipCardCommand $command
     * @param Constraint $constraint
     */
    public function validate($command, Constraint $constraint): void
    {
        if (false === $command instanceof CommandAwareInterface) {
            $this->context->addViolation('Invalid object');

            return;
        }

        if (false === $command->getObjectToPopulate()->isManuallyPaid()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
