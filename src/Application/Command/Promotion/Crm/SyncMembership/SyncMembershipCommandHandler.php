<?php

declare(strict_types=1);

namespace App\Application\Command\Promotion\Crm\SyncMembership;

use App\Domain\Course\Course;
use App\Domain\Membership\CourseGroupValue;
use App\Domain\Membership\MembershipCourse;
use App\Domain\Membership\MembershipCourseGroup;
use App\Domain\Membership\MembershipRule;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Promotion\Action\GreenfeeGuestPercentageDiscountActionCommand;
use App\Infrastructure\Promotion\Action\GreenfeeMemberPercentageDiscountActionCommand;
use App\Infrastructure\Promotion\Rule\DaysInWeekRuleChecker;
use App\Infrastructure\Promotion\Rule\IncludedCoursesRuleChecker;
use App\Infrastructure\Promotion\Rule\NumberOfSimultaneousBookingsRuleChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @super-experimental This command is a kludge. Temporary solution
 */
final class SyncMembershipCommandHandler implements MessageHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(SyncMembershipCommand $command): void
    {
        $membership = $command->getResource();
        if (Membership::STATE_PUBLISHED !== $membership->getState()) {
            throw new BadRequestHttpException('Membership is not published');
        }

        $this->clearMembership($membership);
        $this->syncMembership($membership);
    }

    private function clearMembership(Membership $membership): void
    {
        /** @var MembershipCourseGroup $membershipCourseGroup */
        foreach ($membership->getMembershipCourseGroups() as $membershipCourseGroup) {
            /** @var CourseGroupValue $courseGroupValue */
            foreach ($membershipCourseGroup->getCourseGroupValues() as $courseGroupValue) {
                $this->entityManager->remove($courseGroupValue);
            }
            $this->entityManager->remove($membershipCourseGroup);
        }

        /** @var MembershipCourse $membershipCourse */
        foreach ($membership->getMembershipCourses() as $membershipCourse) {
            $this->entityManager->remove($membershipCourse);
        }
        $this->entityManager->flush();
    }

    private function syncMembership(Membership $membership): void
    {
        $courseRepository = $this->entityManager->getRepository(Course::class);
        $membershipRuleRepository = $this->entityManager->getRepository(MembershipRule::class);

        $daysBookable = $membershipRuleRepository->findOneBy(['type' => 'days_bookable']);
        $groupDiscount = $membershipRuleRepository->findOneBy(['type' => 'group_discount']);
        $bookingLimit = $membershipRuleRepository->findOneBy(['type' => 'booking_limit']);

        foreach ($membership->getPromotions() as $promotion) {
            //todo: find or create new
            $membershipGolfCourseGroup = new MembershipCourseGroup();
            $membershipGolfCourseGroup->setMembership($membership);

            foreach ($promotion->getActions() as $action) {
                if (GreenfeeGuestPercentageDiscountActionCommand::TYPE === $action->getType()) {
                    $guestPercentage = $action->getConfiguration()['percentage_coefficient'] * 100;
                }
                if (GreenfeeMemberPercentageDiscountActionCommand::TYPE === $action->getType()) {
                    $memberPercentage = $action->getConfiguration()['percentage_coefficient'] * 100;
                }
            }

            $groupDiscountValue = new CourseGroupValue();
            $groupDiscountValue->setMembershipCourseGroup($membershipGolfCourseGroup);
            $groupDiscountValue->setMembershipRule($groupDiscount);
            $groupDiscountValue->setValue([
                'owner_discount' => $memberPercentage ?? 100,
                'friend_discount' => $guestPercentage ?? 0,
            ]);
            $this->entityManager->persist($groupDiscountValue);

            foreach ($promotion->getRules() as $rule) {
                if (DaysInWeekRuleChecker::TYPE === $rule->getType()) {
                    $daysBookableValue = new CourseGroupValue();
                    $daysBookableValue->setMembershipCourseGroup($membershipGolfCourseGroup);
                    $daysBookableValue->setMembershipRule($daysBookable);
                    $daysBookableValue->setValue($rule->getConfiguration());
                    $this->entityManager->persist($daysBookableValue);
                }
                if (NumberOfSimultaneousBookingsRuleChecker::TYPE === $rule->getType()) {
                    $bookingLimitValue = new CourseGroupValue();
                    $bookingLimitValue->setMembershipCourseGroup($membershipGolfCourseGroup);
                    $bookingLimitValue->setMembershipRule($bookingLimit);
                    $bookingLimitValue->setValue(['limit' => $rule->getConfiguration()['number_of_simultaneous_bookings']]);
                    $this->entityManager->persist($bookingLimitValue);
                }
                if (IncludedCoursesRuleChecker::TYPE === $rule->getType()) {
                    $courseIds = $rule->getConfiguration();
                }
            }

            foreach ($courseIds as $id) {
                $course = $courseRepository->findOneBy(['id' => $id]);
                if (null === $course) {
                    continue;
                }
                $membershipCourse = new MembershipCourse();
                $membershipCourse->setMembership($membership);
                $membershipCourse->setMembershipCourseGroup($membershipGolfCourseGroup);
                $membershipCourse->setCourse($course);
                $this->entityManager->persist($membershipCourse);
            }

            $this->entityManager->persist($membershipGolfCourseGroup);
        }

        $this->entityManager->persist($membership);
    }
}
