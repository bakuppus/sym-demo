<?php

declare(strict_types=1);

namespace App\Domain\Promotion\Action;

use App\Domain\Promotion\Core\PromotionInterface;
use App\Domain\Promotion\Component\PromotionSubjectInterface;
use App\Domain\Promotion\Component\ConfigurableCommandInterface;

interface PromotionActionCommandInterface extends ConfigurableCommandInterface
{
    public function execute(
        PromotionSubjectInterface $subject,
        array $configuration,
        PromotionInterface $promotion
    ): bool;

    public function revert(
        PromotionSubjectInterface $subject,
        array $configuration,
        PromotionInterface $promotion
    ): void;
}