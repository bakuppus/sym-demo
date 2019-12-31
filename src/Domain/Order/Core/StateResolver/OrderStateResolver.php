<?php

declare(strict_types=1);

namespace App\Domain\Order\Core\StateResolver;

use App\Domain\Order\Component\OrderInterface as BaseOrderInterface;
use App\Domain\Order\Component\OrderStateResolverInterface;
use App\Domain\Order\Core\OrderInterface;
use App\Domain\Order\Order;
use Symfony\Component\Workflow\Registry;

final class OrderStateResolver implements OrderStateResolverInterface
{
    /** @var Registry */
    private $workflow;

    public function __construct(Registry $workflow)
    {
        $this->workflow = $workflow;
    }

    public function resolve(BaseOrderInterface $order): void
    {
        $stateMachine = $this->workflow->get($order, Order::GRAPH);

        if (
            true === $this->canOrderBeFulfilled($order)
            && true === $stateMachine->can($order, Order::TRANSITION_FULFILL)
        ) {
            $stateMachine->apply($order, Order::TRANSITION_FULFILL);
        }
    }

    /**
     * {@inheritDoc}
     * @param OrderInterface|BaseOrderInterface $order
     */
    private function canOrderBeFulfilled(OrderInterface $order): bool
    {
        return Order::PAYMENT_STATE_PAID === $order->getPaymentState();
    }
}
