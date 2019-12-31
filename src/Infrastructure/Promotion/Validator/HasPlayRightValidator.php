<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Domain\Player\Player;
use App\Infrastructure\Shared\Command\CommandAwareInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Domain\Player\PlayRight;

/**
 * @Annotation
 */
final class HasPlayRightValidator extends ConstraintValidator
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * HasPlayRightValidator constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $command
     * @param Constraint $constraint
     */
    public function validate($command, Constraint $constraint): void
    {
        if (false === $command instanceof CommandAwareInterface) {
            $this->context->addViolation('Invalid object');

            return;
        }

        if (true === $command->getObjectToPopulate()->getPlayRightOnly()) {
            $player = $this->entityManager->getRepository(Player::class)->find($command->player);

            $playRight = $player->getPlayRights()->filter(function (PlayRight $playRight) use ($command) {
                return $playRight->getGolfClub() === $command->getObjectToPopulate()->getClub();
            })->first();

            if (false === $playRight) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
