<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PayForOrder;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Application\Query\Order\GetOrderByToken\GetOrderByTokenQuery;
use App\Application\Query\Order\GetOrderByToken\GetOrderByTokenQueryHandler;

final class PayForOrderCommandDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var GetOrderByTokenQueryHandler */
    private $queryHandler;

    public function __construct(GetOrderByTokenQueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $order = $this->queryHandler->handle(new GetOrderByTokenQuery($id));

        $command = new PayForOrderCommand();
        $command->order = $order;
        $command->token = $id;

        return $command;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return PayForOrderCommand::class === $resourceClass;
    }
}