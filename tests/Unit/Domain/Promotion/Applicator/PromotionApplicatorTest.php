<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Promotion\Applicator;

use App\Domain\Promotion\Action\PromotionActionCommandInterface;
use App\Domain\Promotion\Applicator\PromotionApplicator;
use App\Domain\Promotion\Component\PromotionActionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Exception\InvalidCommandActionException;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class PromotionApplicatorTest extends TestCase
{
    public function testApplySuccessfully()
    {
        $action = $this->createMock(PromotionActionInterface::class);
        $action->expects($this->once())->method('getType')->will($this->returnValue('action_type_name'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action])));

        $actionCommand = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand->expects($this->any())->method('getType')->will($this->returnValue('action_type_name'));

        $promotionApplicator = new PromotionApplicator([$actionCommand]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->once())->method('addPromotion')->will($this->returnSelf());

        $promotionApplicator->apply($promotionSubject, $promotion);
    }

    public function testApplyUnsuccessfully()
    {
        $action1 = $this->createMock(PromotionActionInterface::class);
        $action1->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_1'));

        $action2 = $this->createMock(PromotionActionInterface::class);
        $action2->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action1, $action2])));

        $actionCommand1 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand1->expects($this->any())->method('execute')->will($this->returnValue(false));
        $actionCommand1->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_1'));

        $actionCommand2 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand2->expects($this->any())->method('execute')->will($this->returnValue(false));
        $actionCommand2->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotionApplicator = new PromotionApplicator([$actionCommand1, $actionCommand2]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->never())->method('addPromotion')->will($this->returnSelf());

        $promotionApplicator->apply($promotionSubject, $promotion);
    }

    public function testApplyWithInvalidActionCommand()
    {
        $action = $this->createMock(PromotionActionInterface::class);
        $action->expects($this->once())->method('getType')->will($this->returnValue('Invalid_action_type_name'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action])));

        $actionCommand = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand->expects($this->any())->method('getType')->will($this->returnValue('action_type_name'));

        $promotionApplicator = new PromotionApplicator([$actionCommand]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->never())->method('addPromotion');

        $this->expectException(InvalidCommandActionException::class);
        $promotionApplicator->apply($promotionSubject, $promotion);
    }

    public function testApplyWithOnlyOneActionExecutedSuccessfully()
    {
        $action1 = $this->createMock(PromotionActionInterface::class);
        $action1->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_1'));

        $action2 = $this->createMock(PromotionActionInterface::class);
        $action2->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action1, $action2])));

        $actionCommand1 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand1->expects($this->any())->method('execute')->will($this->returnValue(false));
        $actionCommand1->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_1'));

        $actionCommand2 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand2->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand2->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotionApplicator = new PromotionApplicator([$actionCommand1, $actionCommand2]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->once())->method('addPromotion')->will($this->returnSelf());

        $promotionApplicator->apply($promotionSubject, $promotion);
    }

    public function testApplyWithBothActionsExecutedSuccessfully()
    {
        $action1 = $this->createMock(PromotionActionInterface::class);
        $action1->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_1'));

        $action2 = $this->createMock(PromotionActionInterface::class);
        $action2->expects($this->once())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action1, $action2])));

        $actionCommand1 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand1->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand1->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_1'));

        $actionCommand2 = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand2->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand2->expects($this->any())->method('getType')->will($this->returnValue('action_type_name_2'));

        $promotionApplicator = new PromotionApplicator([$actionCommand1, $actionCommand2]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->exactly(1))->method('addPromotion')->will($this->returnSelf());

        $promotionApplicator->apply($promotionSubject, $promotion);
    }

    public function testRevert()
    {
        $action = $this->createMock(PromotionActionInterface::class);
        $action->expects($this->once())->method('getType')->will($this->returnValue('action_type_name'));

        $promotion = $this->createMock(PromotionInterface::class);
        $promotion->method('getActions')->will($this->returnValue(new ArrayCollection([$action])));

        $actionCommand = $this->createMock(PromotionActionCommandInterface::class);
        $actionCommand->expects($this->any())->method('execute')->will($this->returnValue(true));
        $actionCommand->expects($this->any())->method('getType')->will($this->returnValue('action_type_name'));

        $promotionApplicator = new PromotionApplicator([$actionCommand]);

        $promotionSubject = $this->createMock(PromotionSubjectInterface::class);
        $promotionSubject->expects($this->once())->method('removePromotion')->will($this->returnSelf());

        $promotionApplicator->revert($promotionSubject, $promotion);
    }
}