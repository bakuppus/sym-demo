<?php

namespace App\Infrastructure\Elasticsearch\Event\Shared;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use FOS\ElasticaBundle\Doctrine\Listener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;

class PostSoftDeletableSubscriber implements EventSubscriber
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents()
    {
        return [
            SoftDeleteableListener::POST_SOFT_DELETE,
        ];
    }

    public function postSoftDelete(LifecycleEventArgs $args)
    {
        foreach ($this->entityManager->getEventManager()->getListeners(Events::postPersist) as $listener) {
            if ($listener instanceof Listener) {
                $listener->preRemove($args);
                $listener->postFlush();
            }
        }
    }
}
