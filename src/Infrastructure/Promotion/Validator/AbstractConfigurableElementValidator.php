<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Domain\Promotion\Component\ConfigurableCommandInterface;
use App\Domain\Promotion\Component\ConfigurablePromotionElementInterface;
use App\Domain\Promotion\Exception\InvalidConfigurablePromotionException;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

abstract class AbstractConfigurableElementValidator extends ConstraintValidator
{
    /**
     * @param CommandAwareInterface|object $command
     * @param AbstractConfigurableElementConstraint|Constraint $constraint
     */
    public function validate($command, Constraint $constraint): void
    {
        /** @var ConfigurablePromotionElementInterface $configurableElement */
        $configurableElement = $command->getResource();
        if (false === $configurableElement instanceof ConfigurablePromotionElementInterface) {
            $this->context
                ->buildViolation('Invalid command')
                ->addViolation();

            return;
        }

        if (false === $this->isTypeValid($configurableElement->getType())) {
            $this->context->buildViolation($constraint->notValidValueMessage)
                ->atPath($constraint->type)
                ->setParameter('%s', 'type')
                ->addViolation();

            return;
        }

        if (false === $this->isConfigurationValid($configurableElement)) {
            $this->context->buildViolation($constraint->notValidValueMessage)
                ->atPath($constraint->configuration)
                ->setParameter('%s', json_encode($configurableElement->getConfiguration()))
                ->addViolation();
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTypeValid(?string $type): bool
    {
        if (null === $type) {
            return false;
        }

        foreach ($this->getIterable() as $configurableCommand) {
            if ($configurableCommand->getType() === $type) {
                return true;
            }
        }

        return false;
    }

    protected function isConfigurationValid(ConfigurablePromotionElementInterface $configurableElement): bool
    {
        try {
            $configurableCommand = $this->getConfigurableElement($configurableElement->getType());
            $configurableCommand->validateConfiguration($configurableElement->getConfiguration());
        } catch (InvalidConfigurablePromotionException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $type
     *
     * @return ConfigurableCommandInterface
     */
    protected function getConfigurableElement(string $type): ConfigurableCommandInterface
    {
        foreach ($this->getIterable() as $configurableCommand) {
            if ($configurableCommand->getType() === $type) {
                return $configurableCommand;
            }
        }

        throw new InvalidConfigurablePromotionException();
    }

    /**
     * @return iterable|ConfigurableCommandInterface[]
     */
    abstract protected function getIterable(): iterable;
}
