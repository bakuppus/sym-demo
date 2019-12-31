<?php

declare(strict_types=1);

namespace App\Application\Command\Order\GenerateClientToken;

use App\Application\Dto\Order\ClientToken;
use App\Application\Query\Order\GetOrderByToken\GetOrderByTokenQueryHandler;
use App\Infrastructure\Payment\Payum\Braintree\Request\Api\ClientTokenGenerateRequest;
use App\Infrastructure\Payment\Utils\BraintreeGatewayNameGetterTrait;
use Doctrine\Common\Persistence\ManagerRegistry;
use LogicException;
use Payum\Core\Exception\Http\HttpException as PayumHttpException;
use Payum\Core\Payum;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GenerateClientTokenCommandHandler implements MessageHandlerInterface
{
    use BraintreeGatewayNameGetterTrait;

    /** @var GetOrderByTokenQueryHandler */
    private $handler;

    /** @var ManagerRegistry $registry */
    private $registry;

    /** @var Payum $payum */
    private $payum;

    /** @var ParameterBagInterface */
    private $params;

    public function __construct(
        GetOrderByTokenQueryHandler $handler,
        ManagerRegistry $registry,
        Payum $payum,
        ParameterBagInterface $params
    ) {
        $this->handler = $handler;
        $this->registry = $registry;
        $this->payum = $payum;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(GenerateClientTokenCommand $command): ClientToken
    {
        $order = $command->order;

        $customer = $order->getCustomer();

        if (null === $customer) {
            throw new NotFoundHttpException('Player not found');
        }

        $braintreeCustomerId = $customer->getBrainTreeId();

        $requestParams = [];

        if (null !== $braintreeCustomerId) {
            $requestParams['customerId'] = $braintreeCustomerId;
        }

        $request = new ClientTokenGenerateRequest($requestParams);

        try {
            $isSandbox = $this->params->get('braintree_sandbox');
        } catch (ParameterNotFoundException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $gatewayName = $this->getBraintreeGatewayName($isSandbox);

        $gateway = $this->payum->getGateway($gatewayName);

        try {
            $gateway->execute($request);
        } catch (PayumHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $response = $request->getModel();

        $command = new ClientToken();
        $command->clientToken = $response['token'];

        return $command;
    }
}