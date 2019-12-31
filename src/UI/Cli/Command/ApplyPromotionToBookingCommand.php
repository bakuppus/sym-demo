<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Domain\Booking\TeeTimeBooking;
use App\Domain\Promotion\Applicator\PromotionApplicatorInterface;
use App\Domain\Promotion\Checker\CompositePromotionChecker;
use App\Domain\Promotion\Promotion;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApplyPromotionToBookingCommand extends Command
{
    protected static $defaultName = 'promotion:booking:apply {promotion} {booking}';

    /** @var PromotionApplicatorInterface  */
    private $promotionApplicator;

    /** @var ManagerRegistry  */
    private $managerRegistry;

    /** @var CompositePromotionChecker */
    private $promotionChecker;

    public function __construct(
        PromotionApplicatorInterface $promotionApplicator,
        CompositePromotionChecker $promotionChecker,
        ManagerRegistry $managerRegistry,
        string $name = null
    ) {
        parent::__construct($name);
        $this->promotionApplicator = $promotionApplicator;
        $this->managerRegistry = $managerRegistry;
        $this->promotionChecker = $promotionChecker;
    }

    public function configure()
    {
        $this
            ->addArgument('promotion', InputArgument::REQUIRED)
            ->addArgument('booking', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $booking = $input->getArgument('booking');
        $promotion = $input->getArgument('promotion');

        $booking = $this->managerRegistry->getRepository(TeeTimeBooking::class)->find($booking);
        $promotion = $this->managerRegistry->getRepository(Promotion::class)->find($promotion);
        if (null === $booking) {
            $symfonyStyle->error('Booking doesn\'t exist');

            return;
        }

        if (null === $promotion) {
            $symfonyStyle->error('Promotion doesn\'t exist');

            return;
        }

        if (false === $this->promotionChecker->isEligible($booking, $promotion)) {
            $symfonyStyle->error('Promotion is invalid for this booking');

            return;
        }

        $this->promotionApplicator->apply($booking, $promotion);
        $this->managerRegistry->getManager()->persist($booking);
        $this->managerRegistry->getManager()->flush();

        $symfonyStyle->success('Promotion applied');
    }
}