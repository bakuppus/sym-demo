<?php

namespace App\Application\Event;

use Symfony\Component\Workflow\Event\Event;

interface EventSubjectSubscriberInterface
{
    public function getSubject(Event $event): object;
}
