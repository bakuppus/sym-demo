<?php

declare(strict_types=1);

namespace App\Application\Command\Order\SetOrderPaymentRefund;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Order\Order;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Workflow\Registry;

final class OrderPaymentRefundCommandHandler implements MessageHandlerInterface
{
    /** @var Registry */
    private $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function __invoke(OrderPaymentRefundCommand $command): OrderInterface
    {
        $order = $command->getResource();

        $workflow = $this->workflowRegistry->get($order, Order::PAYMENT_GRAPH);
        $workflow->apply($order, Order::PAYMENT_TRANSITION_REFUND);

        return $order;
    }
}