<?php

declare(strict_types=1);

namespace App\Application\Query\Order\GetOrderByToken;

use App\Domain\Order\Order;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetOrderByTokenQueryHandler
{
    /** @var ManagerRegistry */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(GetOrderByTokenQuery $query)
    {
        $repository = $this->managerRegistry->getManager()->getRepository(Order::class);

        $order = $repository->findOneBy([
            'token' => $query->getToken(),
        ]);

        if (null === $order) {
            throw new NotFoundHttpException('Order not found');
        }

        return $order;
    }
}