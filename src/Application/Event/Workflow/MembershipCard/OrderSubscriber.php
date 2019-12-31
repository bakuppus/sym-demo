<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\MembershipCard;

use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Order\Item\OrderItemMembershipCard;
use App\Domain\Order\Order;
use App\Domain\Promotion\MembershipCard;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;

class OrderSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var Registry */
    private $workflow;

    /** @var OrderItemMembershipCard */
    private $orderItem;

    public function __construct(Registry $workflow)
    {
        $this->workflow = $workflow;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.order.completed.create' => 'onCompletedCreate',
            'workflow.order_payment.completed.pay' => 'onCompletedPay',
        ];
    }

    public function onCompletedCreate(Event $event): void
    {
        $subject = $this->getSubject($event);
        if (false === $subject instanceof Order) {
            return;
        }

        $this->orderItem = $subject->getItems()->first();

        if (false === $this->orderItem) {
            return;
        }

        $membershipCard = $this->orderItem->getMembershipCard();
        $membershipCard->setOrder($subject);
    }

    public function onCompletedPay(Event $event): void
    {
        $subject = $event->getSubject();

        if (false === $subject instanceof Order) {
            return;
        }

        /** @var OrderItemMembershipCard $orderItemMembershipCard */
        $orderItemMembershipCard = $subject->getItems()->first();

        if (false === $orderItemMembershipCard) {
            return;
        }

        $resource = $orderItemMembershipCard->getMembershipCard();
        $workflow = $this->workflow->get($resource, MembershipCard::WORKFLOW_NAME);

        if (false === $workflow->can($resource, MembershipCard::TRANSITION_PAY)) {
            return;
        }

        try {
            $workflow->apply($resource, MembershipCard::TRANSITION_PAY);
        } catch (LogicException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return Order|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }
}
