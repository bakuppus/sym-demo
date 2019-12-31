<?php

declare(strict_types=1);

namespace App\Domain\Accounting;

use Doctrine\Common\Collections\Collection;

interface SubjectFeeInterface
{
    public function getFees(): Collection;

    public function addFee(FeeInterface $fee): SubjectFeeInterface;

    public function removeFee(FeeInterface $fee): SubjectFeeInterface;

    public function hasFee(FeeInterface $fee): bool;
}
