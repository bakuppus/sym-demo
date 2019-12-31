<?php

declare(strict_types=1);

namespace App\Domain\Price;

use App\Domain\Price\Exception\PriceModuleSettingsException;
use Validator;

trait PriceModuleStaticTrait
{

    /**
     * {@inheritdoc}
     *
     */
    public function validate(): void
    {
        $this->isValidSettings();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function isValidSettings(): void
    {
        if (null === $this->id) {
            return;
        }

        $validator = Validator::make($this->settings, $this->settingsRules);

        if (true === $validator->fails()) {
            throw new PriceModuleSettingsException($validator->messages()->first());
        }
    }
}
