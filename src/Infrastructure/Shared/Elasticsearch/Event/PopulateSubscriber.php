<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Elasticsearch\Event;

use FOS\ElasticaBundle\Persister\Event\Events;
use FOS\ElasticaBundle\Persister\Event\PrePersistEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PopulateSubscriber implements EventSubscriberInterface
{
    /** @var int sec ( 90 minutes) */
    private $limit;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->limit = $bag->get('limit_overall_reply_time');
    }

    public function onPrePersist(PrePersistEvent $event): void
    {
        $options = $event->getOptions();

        $options = array_replace($options, [
            'limit_overall_reply_time' => $this->limit,
        ]);

        $event->setOptions($options);
    }

    public static function getSubscribedEvents()
    {
        return [Events::PRE_PERSIST => 'onPrePersist'];
    }
}
