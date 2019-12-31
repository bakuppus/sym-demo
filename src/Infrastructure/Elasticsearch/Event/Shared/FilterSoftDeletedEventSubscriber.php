<?php

namespace App\Infrastructure\Elasticsearch\Event\Shared;

use FOS\ElasticaBundle\Persister\Event\Events;
use FOS\ElasticaBundle\Persister\Event\PreInsertObjectsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FilterSoftDeletedEventSubscriber implements EventSubscriberInterface
{
    public function filterObjects(PreInsertObjectsEvent $event)
    {
        $filtered = [];
        foreach ($event->getObjects() as $object) {
            if (true === method_exists($object, 'isDeleted') && true === $object->isDeleted()) {
                continue;
            }

            $filtered[] = $object;
        }

        $event->setObjects($filtered);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [Events::PRE_INSERT_OBJECTS => 'filterObjects'];
    }
}
