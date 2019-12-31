<?php

declare(strict_types=1);

namespace App\Domain\Price;

use App\Domain\Price\Exception\PriceModuleParametersException;
use App\Domain\Price\Exception\PriceModuleSettingsException;
use Validator;

trait PriceModuleDynamicTrait
{
    public function validate(): void
     {
         $this->isValidSettings();
         $this->isValidParameters();
     }

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

    public function isValidParameters(): void
    {
        if (null === $this->id || null === $this->parameters) {
            return;
        }

        $validator = Validator::make($this->parameters, $this->parametersRules);

        if (true === $validator->fails()) {
            throw new PriceModuleParametersException($validator->messages()->first());
        }
    }
}
