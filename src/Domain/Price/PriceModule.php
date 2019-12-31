<?php

declare(strict_types=1);

namespace App\Domain\Price;

use App\Infrastructure\Shared\Doctrine\Traits\Mapping\GeneratedValue\Strategy\AutoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Validator;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *      @ORM\UniqueConstraint(name="period_price_module", columns={"price_period_id", "discr"})
 * })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *      "base_price"    = "App\Domain\Price\ValueObject\BasePrice",
 *      "min_price" = "App\Domain\Price\ValueObject\MinPrice",
 *      "round_price"   = "App\Domain\Price\ValueObject\RoundPrice",
 *      "demand_daily"   = "App\Domain\Price\ValueObject\DemandDaily",
 *      "demand_historical"  = "App\Domain\Price\ValueObject\DemandHistorical"
 * })
 */
abstract class PriceModule implements PriceModuleSingleInheritanceInterface
{
    use AutoTrait;
    use TimestampableEntity;

    /**
     * @var PricePeriod
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Price\PricePeriod", inversedBy="priceModules", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pricePeriod;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $settings;

    /**
     * @var array
     *
     * @ORM\Column(type="json", nullable=true)
     */
    protected $parameters;

    /**
     * @var int
     *
     * @ORM\Column(name="`order`", type="smallint")
     */
    private $order;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getPricePeriod(): PricePeriod
    {
        return $this->pricePeriod;
    }

    public function setPricePeriod(PricePeriod $pricePeriod): self
    {
        $this->pricePeriod = $pricePeriod;

        return $this;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): self
    {
        // TODO: Refactor `collect` method
        /*$this->settings = collect($settings)->only(array_keys($this->settingsRules))->toArray();*/

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(?array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getModuleName(): string
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    /**
     * {@inheritdoc}
     *
     * @ORM\PrePersist @ORM\PreUpdate
     */
    abstract public function validate(): void;
}
