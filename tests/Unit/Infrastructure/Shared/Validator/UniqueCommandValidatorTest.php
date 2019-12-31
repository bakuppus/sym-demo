<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Shared\Validator;

use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Application\Command\Club\Crm\AddNewMembership\AddNewMembershipCommand;
use App\Domain\Club\Club;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Validator\UniqueCommand;
use App\Infrastructure\Shared\Validator\UniqueCommandValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UniqueCommandValidatorTest extends TestCase
{
    /** @var UniqueCommand */
    protected $constraint;

    /** @var UniqueCommandValidator */
    protected $validator;

    /** @var ExecutionContextInterface */
    protected $context;

    public function testValidateUniqueness()
    {
        $club = $this->createMock(Club::class);
        $membership = $this->createMock(Membership::class);

        $membershipRepository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $membershipRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($membership));

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($membershipRepository));

        $command = new AddNewMembershipCommand();
        $command->name = 'Membership';
        $command->populate([AbstractItemNormalizer::OBJECT_TO_POPULATE => $club]);

        $this->constraint = $this->createConstraint($club);
        $this->validator = $this->createValidator($entityManager);
        $this->context = $this->createContext($this->constraint);

        $this->validator->initialize($this->context);
        $this->validator->validate($command, $this->constraint);

        $this->assertViolationRaised();
    }

    protected function createConstraint($club)
    {
        return new UniqueCommand([
            'targetEntity' => "App\Domain\Promotion\Membership",
            'message' => 'myMessage',
            'uniqueFields' => ['name', mb_strtolower(get_class($club))],
        ]);
    }

    protected function createValidator($entityManager)
    {
        return new UniqueCommandValidator($entityManager);
    }

    protected function createContext($constraint)
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();

        $context = new ExecutionContext($validator, 'root', $translator);
        $context->setNode('Invalid value', null, null, 'proterty.path');
        $context->setConstraint($constraint);

        return $context;
    }

    protected function assertViolationRaised()
    {
        $violations = $this->context->getViolations();
        $violationsCount = count($violations);

        $this->assertSame(
            1,
            $violationsCount,
            sprintf('1 violation expected. Got %u.', $violationsCount)
        );

        $this->assertEquals('myMessage', $violations[0]->getConstraint()->message);
        $this->assertEquals($this->constraint, $violations[0]->getConstraint());
    }
}
