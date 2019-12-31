<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Order;

use App\Application\Command\Order\CreateOrder\CreateOrderMembershipCommand;
use App\Application\Command\Order\CreateOrder\Item\CreateOrderItemMembershipCardCommand;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Club\Club;
use App\Domain\Payment\Payment;
use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\MembershipCard;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;

final class MembershipCardSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function onCreate(Event $event): void
    {
        $subject = $this->getSubject($event);

        if (false === $subject instanceof MembershipCard) {
            return;
        }

        $order = new CreateOrderMembershipCommand();
        $order->membership = $subject->getMembership();
        $order->club = $subject->getMembership()->getClub();
        $order->customer = $subject->getPlayer();
        $order->localeCode = Club::LOCALE_CODE;
        $order->currencyCode = Payment::CURRENCY_SEK;

        $item = new CreateOrderItemMembershipCardCommand();
        $item->membershipCard = $subject;
        $item->total = $subject->getMembership()->getTotal();

        $order->items = [$item];

        $this->messageBus->dispatch($order);
    }

    public function onCancel(Event $event): void
    {
        // TODO: Finish on membership cancel/remove
        $subject = $this->getSubject($event);

        if (false === $subject instanceof MembershipCard) {
            return;
        }
    }

    /**
     * {@inheritDoc}
     * @return MembershipCardInterface|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.membership_card.completed.create' => 'onCreate',
//            TODO: Finish in next feature
//            'workflow.membership_card.completed.cancel' => 'onCancel',
//            'workflow.membership_card.completed.remove' => 'onRemove',
        ];
    }
}
