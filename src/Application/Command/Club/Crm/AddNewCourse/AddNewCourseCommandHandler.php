<?php

declare(strict_types=1);

namespace App\Application\Command\Club\Crm\AddNewCourse;

use App\Domain\Course\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AddNewCourseCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(AddNewCourseCommand $command): Course
    {
        $source = $command->getResource();
        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
