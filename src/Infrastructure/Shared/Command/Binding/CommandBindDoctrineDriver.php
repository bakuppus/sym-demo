<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command\Binding;

use App\Infrastructure\Shared\Command\Binding\Configuration\CommandBind;
use App\Infrastructure\Shared\Command\Binding\Model\Property;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use ReflectionObject;
use ReflectionProperty;

final class CommandBindDoctrineDriver implements CommandBindDriverInterface
{
    /** @var Reader */
    private $reader;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(Reader $reader, EntityManagerInterface $entityManager)
    {
        $this->reader = $reader;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function bind(CommandAwareInterface $command): void
    {
        $reflectedCommand = new ReflectionObject($command);

        foreach ($reflectedCommand->getProperties() as $reflectionProperty) {
            $configuration = $this->getConfiguration($reflectionProperty);
            $property = new Property($reflectionProperty->getName(), $reflectionProperty->getValue($command));

            if (null === $configuration) {
                continue;
            }

            $this->process($property, $configuration, $command);
        }
    }

    /**
     * @param Property $property
     * @param CommandBind $configuration
     * @param CommandAwareInterface $command
     *
     * @throws EntityNotFoundException
     */
    protected function process(Property $property, CommandBind $configuration, CommandAwareInterface $command): void
    {
        if (false === $configuration->isTraverse && true === $property->isInt()) {
            $this->apply($property, $configuration, $command);
        }

        if (true === $configuration->isTraverse && true === $property->isObject()) {
            self::bind($property->value);
        }

        if (true === $configuration->isTraverse && true === $property->isArray()) {
            $this->applyCollection($property);
        }
    }

    /**
     * @param Property $property
     * @param CommandBind $configuration
     * @param CommandAwareInterface $command
     *
     * @throws EntityNotFoundException
     */
    protected function apply(Property $property, CommandBind $configuration, CommandAwareInterface $command): void
    {
        $identifier = (int)$property->value;

        $item = $this->entityManager
            ->getRepository($configuration->targetEntity)
            ->find($identifier);

        if (null === $item) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($configuration->targetEntity, [$identifier]);
        }

        $command->{$property->name} = $item;
    }

    /**
     * @param Property $property
     *
     * @throws EntityNotFoundException
     */
    protected function applyCollection(Property $property): void
    {
        $commands = $property->value;

        foreach ($commands as $command) {
            self::bind($command);
        }
    }

    /**
     * @param ReflectionProperty $property
     *
     * @return CommandBind|object|null
     */
    protected function getConfiguration(ReflectionProperty $property): ?CommandBind
    {
        return $this->reader->getPropertyAnnotation($property, CommandBind::class);
    }
}
