<?php

declare(strict_types=1);

namespace App\Domain\Accounting;

interface FeeInterface
{
    public function getFeeUnit(): ?FeeUnitInterface;

    public function setFeeUnit(?FeeUnitInterface $feeUnit): FeeInterface;

    public function getVat(): ?int;

    public function setVat(?int $vat): FeeInterface;

    public function getPrice(): ?int;

    public function setPrice(?int $price): FeeInterface;

    public function getSubject(): ?SubjectFeeInterface;

    public function setSubject(?SubjectFeeInterface $subject): FeeInterface;
}
