<?php

declare(strict_types=1);

namespace App\Infrastructure\Payment\Workflow;

trait PaymentWorkflowContextPreparationTrait
{
    public function preparePaymentDetailsContext(array $details): array
    {
        return ['details' => $details];
    }
}