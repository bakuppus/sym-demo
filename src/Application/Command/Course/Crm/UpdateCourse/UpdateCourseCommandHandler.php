<?php

declare(strict_types=1);

namespace App\Application\Command\Course\Crm\UpdateCourse;

use App\Domain\Course\Course;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateCourseCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UpdateCourseCommand $command
     *
     * @return Course|object
     * @throws Exception
     */
    public function __invoke(UpdateCourseCommand $command): Course
    {
        $source = $command->getResource();
        //todo: createdAt and updatedAt don't merge @see https://github.com/Atlantic18/DoctrineExtensions/issues/1255
        $source = $this->entityManager->merge($source);

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
