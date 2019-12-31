<?php

declare(strict_types=1);

namespace App\Application\Event\Platform\Order;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Application\Command\Promotion\Crm\AddCardToMembership\AddCardToMembershipCommand;
use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Infrastructure\Shared\Mailer\Order\OrderMembershipPaymentLinkSender;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class SendPaymentLinkSubscriber implements EventSubscriberInterface
{
    /** @var OrderMembershipPaymentLinkSender */
    private $mailer;

    /** @var MembershipCardInterface */
    private $subject;

    /** @var AddCardToMembershipCommand */
    private $command;

    public function __construct(OrderMembershipPaymentLinkSender $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     * @throws TransportExceptionInterface
     */
    public function sendMail(ViewEvent $event)
    {
        $this->subject = $event->getControllerResult();
        $this->command = $event->getRequest()->attributes->get('data');
        $method = $event->getRequest()->getMethod();

        if (null === $this->command || false === $this->command instanceof AddCardToMembershipCommand) {
            return;
        }

        if (
            false === $this->subject instanceof MembershipCardInterface
            || $method !== Request::METHOD_PUT
            || false === $this->command->isSendPaymentLink
        ) {
            return;
        }

        $this->mailer->send($this->subject->getOrder());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }
}
