<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Command\Binding\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Infrastructure\Shared\Command\Binding\CommandBindDriverInterface;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class CommandBindSubscriber implements EventSubscriberInterface
{
    /** @var CommandBindDriverInterface */
    private $driver;

    public function __construct(CommandBindDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['bind', EventPriorities::POST_VALIDATE],
        ];
    }

    /**
     * @param ViewEvent $event
     *
     * @throws EntityNotFoundException
     */
    public function bind(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if (false === ($result instanceof CommandAwareInterface)) {
            return;
        }

        $this->driver->bind($result);
    }
}
