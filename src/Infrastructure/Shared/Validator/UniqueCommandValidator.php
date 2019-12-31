<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Validator;

use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use function is_array;
use function is_string;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @Annotation
 */
final class UniqueCommandValidator extends ConstraintValidator
{
    /** @var EntityManager */
    private $entityManager;

    /** @var Constraint */
    private $constraint;

    /** @var CommandAwareInterface */
    private $command;

    /** @var string */
    private $populatedField;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $command
     * @param Constraint $constraint
     *
     * @throws ReflectionException
     */
    public function validate($command, Constraint $constraint): void
    {
        if (false === $command instanceof CommandAwareInterface) {
            $this->context->addViolation('Invalid object');

            return;
        }

        $this->command = $command;

        if (false === $constraint instanceof UniqueCommand) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UniqueEntity');
        }

        $this->constraint = $constraint;

        if (
            false === is_array($this->constraint->uniqueFields)
            && false === is_string($this->constraint->uniqueFields)
        ) {
            throw new UnexpectedTypeException($this->constraint->uniqueFields, 'array');
        }

        if (
            null !== $this->constraint->errorPath
            && false === is_string($this->constraint->errorPath)
        ) {
            throw new UnexpectedTypeException($this->constraint->errorPath, 'string or null');
        }

        if (0 === count((array) $this->constraint->uniqueFields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $this->validateUniqueFields();

        if (null === $entity = $this->getEntity($this->populatedField)) {
            return;
        }

        $errorPath = null !== $constraint->errorPath
            ? $constraint->errorPath
            : $this->constraint->uniqueFields[0];

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->setCause($entity)
            ->addViolation();
    }

    /**
     * @param object $populatedObject
     *
     * @return string|null
     * @throws ReflectionException
     */
    private function getPopulatedField(object $populatedObject): ?string
    {
        if (null === $populatedObject) {
            return null;
        }

        $reflection = new ReflectionClass($populatedObject);
        $fieldName = $reflection->getShortName();

        return mb_strtolower($fieldName);
    }

    /**
     * @throws ReflectionException
     */
    private function validateUniqueFields(): void
    {
        $this->populatedField = $this->getPopulatedField($this->command->getObjectToPopulate());

        $errors = [];
        foreach ((array) $this->constraint->uniqueFields as $field) {
            if (false === property_exists($this->command, $field) && $this->populatedField !== $field) {
                $errors[] = "'{$field}' field does not exist among the command properties.";
            }
        }

        if (count($errors) > 0) {
            $message = implode(' ', $errors);
            throw new ValidatorException($message);
        }
    }

    private function getEntity(string $populatedField): ?object
    {
        $criteria = [];

        foreach ((array) $this->constraint->uniqueFields as $field) {
            $criteria[$field] = $field === $populatedField
                ? $this->command->getObjectToPopulate()
                : $this->command->{$field};
        }

        return $this
            ->entityManager
            ->getRepository($this->constraint->targetEntity)
            ->findOneBy($criteria);
    }
}
