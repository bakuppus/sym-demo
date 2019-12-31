<?php

declare(strict_types=1);

namespace App\Application\Command\Order\SetOrderPaymentRefund;

use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPaymentRefundCommandTransformer
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     * @param CommandPopulatableInterface $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $object->populate($context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Order) {
            return false;
        }

        $classMetadata = $this->entityManager->getClassMetadata($to);
        $isOrderInheritance = in_array($to, $classMetadata->discriminatorMap);

        return true === $isOrderInheritance && OrderPaymentRefundCommand::class === $context['input']['class'];
    }
}