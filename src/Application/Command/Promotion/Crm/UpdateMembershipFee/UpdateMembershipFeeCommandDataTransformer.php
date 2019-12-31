<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\UpdateMembershipFee;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Accounting\Fee;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class UpdateMembershipFeeCommandDataTransformer implements DataTransformerInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * AddNewMembershipFeeCommandDataTransformer constructor.
     *
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     *
     * @param CommandPopulatableInterface $object
     */
    public function transform($object, string $to, array $context = [])
    {
        $object->populate($context);

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Fee) {
            return false;
        }

        return Fee::class === $to && UpdateMembershipFeeCommand::class === $context['input']['class'];
    }
}
