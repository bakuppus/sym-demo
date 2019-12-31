<?php

declare(strict_types=1);

namespace App\Domain\Club\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Billing
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $companyAddress;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $organisationNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vatNumber;

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): Billing
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->companyAddress;
    }

    public function setCompanyAddress(?string $companyAddress): Billing
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Billing
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Billing
    {
        $this->email = $email;

        return $this;
    }

    public function getOrganisationNumber(): ?string
    {
        return $this->organisationNumber;
    }

    public function setOrganisationNumber(?string $organisationNumber): Billing
    {
        $this->organisationNumber = $organisationNumber;

        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): Billing
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }
}