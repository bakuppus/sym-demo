<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Validator;

use App\Domain\Promotion\Action\PromotionActionCommandInterface;

class IsValidActionInputValidator extends AbstractConfigurableElementValidator
{
    /** @var iterable|PromotionActionCommandInterface[] $iterable */
    protected $actionCommands;

    public function __construct(iterable $actionCommands)
    {
        $this->actionCommands = $actionCommands;
    }

    /**
     * @return iterable|PromotionActionCommandInterface[]
     */
    protected function getIterable(): iterable
    {
        return $this->actionCommands;
    }
}
