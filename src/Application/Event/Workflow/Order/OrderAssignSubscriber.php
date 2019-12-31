<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Order;

use App\Application\Assigner\Order\Number\OrderNumberAssignerInterface;
use App\Application\Assigner\Order\Token\OrderTokenAssignerInterface;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Order\Order;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

final class OrderAssignSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var OrderNumberAssignerInterface */
    private $numberAssigner;

    /** @var OrderTokenAssignerInterface */
    private $tokenAssigner;

    public function __construct(
        OrderNumberAssignerInterface $numberAssigner,
        OrderTokenAssignerInterface $tokenAssigner
    ) {
        $this->numberAssigner = $numberAssigner;
        $this->tokenAssigner = $tokenAssigner;
    }

    public function assignNumber(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Order) {
            return;
        }

        $this->numberAssigner->assignNumber($subject);
    }

    public function assignToken(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof Order) {
            return;
        }

        $this->tokenAssigner->assignTokenIfNotSet($subject);
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.order.completed.create' => [
                ['assignNumber', 10],
                ['assignToken', 9],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     * @return Order|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }
}
