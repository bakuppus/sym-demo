<?php

declare(strict_types=1);

namespace App\Application\Event\Workflow\Payment;

use App\Application\Command\Payment\CompletePayment\CompletePaymentCommand;
use App\Application\Command\Payment\RefundPayment\RefundPaymentCommand;
use App\Application\Event\EventSubjectSubscriberInterface;
use App\Domain\Payment\Payment;
use App\Application\Service\Payment\Strategy\UpdatePaymentMethod\OnSitePaymentMethodStrategy;
use App\Application\Service\Payment\Strategy\UpdatePaymentMethod\UpdatePaymentMethodContext;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Promotion\Core\MembershipCardInterface;
use App\Domain\Promotion\MembershipCard;
use LogicException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Registry;

final class MembershipCardSubscriber implements EventSubscriberInterface, EventSubjectSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var Registry */
    private $workflow;

    /** @var UpdatePaymentMethodContext */
    private $updatePaymentMethodContext;

    public function __construct(
        MessageBusInterface $messageBus,
        Registry $workflow,
        UpdatePaymentMethodContext $updatePaymentMethodContext
    ) {
        $this->messageBus = $messageBus;
        $this->workflow = $workflow;
        $this->updatePaymentMethodContext = $updatePaymentMethodContext;
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.membership_card.completed.pay' => 'completePayment',
            'workflow.membership_card.entered.canceled' => 'refundPayment',
        ];
    }

    public function completePayment(Event $event): void
    {
        $membershipCard = $this->getSubject($event);

        $payment = $this->getPaymentFromMembershipCard($membershipCard);

        $stateMachine = $this->workflow->get($payment, Payment::GRAPH);

        if (false === $stateMachine->can($payment, Payment::TRANSITION_COMPLETE)) {
            return;
        }

        $payment = $this->updatePaymentMethodContext
            ->updatePaymentMethod($payment, OnSitePaymentMethodStrategy::TYPE);

        $completePayment = new CompletePaymentCommand();
        $completePayment->setPayment($payment);

        $this->messageBus->dispatch($completePayment);
    }

    public function refundPayment(Event $event): void
    {
        $membershipCard = $this->getSubject($event);

        $payment = $this->getPaymentFromMembershipCard($membershipCard);

        $refundPayment = new RefundPaymentCommand();
        $refundPayment->setPayment($payment);

        $this->messageBus->dispatch($refundPayment);
    }

    /**
     * {@inheritDoc}
     * @return MembershipCard|object
     */
    public function getSubject(Event $event): object
    {
        return $event->getSubject();
    }

    private function getPaymentFromMembershipCard(MembershipCardInterface $membershipCard): PaymentInterface
    {
        $order = $membershipCard->getOrder();

        $payments = $order->getPayments();

        if (true === $payments->isEmpty()) {
            throw new LogicException('There are no payments for order to mark membership as paid');
        }

        /**
         * @TODO Think about multiple payments and how to handle them
         */
        $payment = $payments->last();

        return $payment;
    }
}
