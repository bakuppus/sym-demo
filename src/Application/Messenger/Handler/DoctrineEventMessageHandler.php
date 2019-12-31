<?php

declare(strict_types=1);

namespace App\Application\Messenger\Handler;

use App\Application\Messenger\Message\DoctrineEventMessage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Doctrine\Listener;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\Event\ListenersInvoker;

class DoctrineEventMessageHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ListenersInvoker */
    protected $listenersInvoker;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->listenersInvoker = new ListenersInvoker($this->entityManager);
    }

    public function __invoke(DoctrineEventMessage $doctrineEventMessage): void
    {
        $class = $this->laravelEntityToApiPlatform($doctrineEventMessage->getLaravelEntity());
        if (null === $class) {
            return;
        }

        $classMetaData = $this->entityManager->getClassMetadata($class);

        switch ($doctrineEventMessage->getEventName()) {
            case Events::postRemove:
                $entity = $this->entityManager
                    ->getUnitOfWork()
                    ->createEntity(
                        $class,
                        $doctrineEventMessage->getOriginalEntityData()
                    );
                break;
            default:
                $entity = $this->entityManager
                    ->getRepository($class)
                    ->find($doctrineEventMessage->getIdentification());
                break;
        }

        $invoke = $this->listenersInvoker->getSubscribedSystems(
            $classMetaData,
            $doctrineEventMessage->getEventName()
        );
        $this->listenersInvoker->invoke(
            $classMetaData,
            $doctrineEventMessage->getEventName(),
            $entity,
            new LifecycleEventArgs($entity, $this->entityManager),
            $invoke
        );

        foreach ($this->entityManager->getEventManager()->getListeners(Events::postPersist) as $listener) {
            if ($listener instanceof Listener) {
                $listener->postFlush();
            }
        }

        $this->entityManager->clear();
    }

    public function laravelEntityToApiPlatform(string $laravelEntity)
    {
        $mapping = [
            'App\DAO\Entities\Player' => 'App\Domain\Player\Player',
            'App\DAO\Entities\TeeTimeBooking' => 'App\Domain\Booking\TeeTimeBooking',
            'App\DAO\Entities\PlayerMembership' => 'App\Domain\Promotion\MembershipCard',
            'App\DAO\Entities\PlayerMembershipToAssign' => 'App\Domain\Membership\PlayerMembershipToAssign',
            'App\DAO\Entities\PlayRight' => 'App\Domain\Player\PlayRight',
        ];

        return $mapping[$laravelEntity] ?? null;
    }
}
