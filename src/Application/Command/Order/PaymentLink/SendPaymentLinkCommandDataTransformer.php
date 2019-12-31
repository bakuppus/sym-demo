<?php

declare(strict_types=1);

namespace App\Application\Command\Order\PaymentLink;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Order\Order;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use Doctrine\ORM\EntityManagerInterface;

final class SendPaymentLinkCommandDataTransformer implements DataTransformerInterface
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

        return true === $isOrderInheritance && SendPaymentLinkCommand::class === $context['input']['class'];
    }
}
