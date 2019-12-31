<?php

declare(strict_types=1);

namespace App\Infrastructure\Elasticsearch\Event\Course;

use App\Domain\Course\Course;
use App\Infrastructure\Elasticsearch\Event\Shared\ElasticHydrationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CourseElasticHydrationEvent implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ElasticHydrationEvent::class => 'onElasticHydration',
        ];
    }

    public function onElasticHydration(ElasticHydrationEvent $event)
    {
        $result = $event->getHybridResult();
        $course = $result->getTransformed();
        $elasticResult = $result->getResult();
        if (false === $course instanceof Course
            || false === $elasticResult->hasFields()
            || false === isset($elasticResult->getFields()['distance'])
        ) {
            return;
        }

        /** @var Course $course */
        $distance = $elasticResult->getFields()['distance'][0];
        $course->setDistance($distance);
    }
}
