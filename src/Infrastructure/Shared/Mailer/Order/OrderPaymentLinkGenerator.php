<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Order;

use App\Domain\Order\Core\OrderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class OrderPaymentLinkGenerator implements OrderPaymentLinkGeneratorInterface
{
    private const PATH = 'order';

    /** @var ParameterBagInterface */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function generate(OrderInterface $order): string
    {
        $host = $this->parameterBag->get('payment_host');

        return sprintf('%s/%s/%s', $host, self::PATH, $order->getToken());
    }
}
