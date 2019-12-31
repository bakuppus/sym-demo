<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Domain\Promotion\Core\MembershipInterface;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Workflow\Registry;

/**
 * @Anotation
 */
final class CanPublishMembershipValidator extends ConstraintValidator
{
    /** @var Registry */
    private $stateMachineRegistry;

    public function __construct(Registry $stateMachineRegistry)
    {
        $this->stateMachineRegistry = $stateMachineRegistry;
    }

    /**
     * @param CommandAwareInterface $command
     * @param Constraint|CanPublishMembership $constraint
     */
    public function validate($command, Constraint $constraint)
    {
        if (false === $command instanceof CommandAwareInterface) {
            $this->context->addViolation('Invalid object');

            return;
        }

        /** @var MembershipInterface $membership */
        $membership = $command->getResource();
        if (false === $membership instanceof MembershipInterface) {
            $this->context->addViolation('Invalid resource');

            return;
        }

        if (false === $this->canBePublished($membership)) {
            $this->context->buildViolation('Membership can\'t be published')
                ->atPath($constraint->property)
                ->addViolation();
        }
    }

    private function canBePublished(MembershipInterface $membership): bool
    {
        $stateMachine = $this->stateMachineRegistry->get($membership, Membership::GRAPH);

        if (false === $stateMachine->can($membership, Membership::TRANSITION_PUBLISH)) {
            return false;
        }

        if (0 === $membership->getPromotions()->count()) {
            return false;
        }

        foreach ($membership->getPromotions() as $promotion) {
            if (0 === $promotion->getActions()->count()) {
                return false;
            }
            if (0 === $promotion->getRules()->count()) {
                return false;
            }
        }

        return true;
    }
}