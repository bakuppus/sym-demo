<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\AddNewMembershipFee;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Command\CommandPopulatableInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class AddNewMembershipFeeCommandDataTransformer implements DataTransformerInterface
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
        if ($data instanceof Membership) {
            return false;
        }

        return Membership::class === $to && AddNewMembershipFeeCommand::class === $context['input']['class'];
    }
}
