<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment;

use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Item;
use App\Infrastructure\Shared\Mailer\Payment\Model\Receipt;
use App\Infrastructure\Shared\Mailer\Payment\Model\Total;
use App\Infrastructure\Shared\Mailer\Payment\Strategy\Info\InfoContext;
use App\Infrastructure\Shared\Mailer\Payment\Strategy\Item\ItemContext;
use App\Infrastructure\Shared\Mailer\Payment\Strategy\Meta\MetaContext;

final class ReceiptGenerator
{
    /** @var InfoContext */
    private $infoContext;

    /** @var MetaContext */
    private $metaContext;

    /** @var ItemContext */
    private $itemContext;

    public function __construct(InfoContext $infoContext, MetaContext $metaContext, ItemContext $itemContext)
    {
        $this->infoContext = $infoContext;
        $this->metaContext = $metaContext;
        $this->itemContext = $itemContext;
    }

    public function getReceipt(PaymentInterface $payment): Receipt
    {
        $info = $this->infoContext->getInfo($payment);
        $meta = $this->metaContext->getMeta($payment);

        $items = $this->itemContext->getItems($payment);
        $total = $this->getTotalFromItems($items);

        $receipt = new Receipt();
        $receipt->setTotal($total);
        $receipt->setInfo($info);
        $receipt->setItems($items);
        $receipt->setMeta($meta);

        return $receipt;
    }

    /**
     * @param array|Item[] $items
     *
     * @return Total
     */
    private function getTotalFromItems(array $items): Total
    {
        $total = 0;
        $netSumTotal = 0;
        $vatTotal = 0;

        foreach ($items as $item) {
            $total += $item->getTotal();
            $netSumTotal += $item->getNetSum();
            $vatTotal += $item->getVat();
        }

        $itemsTotal = new Total();
        $itemsTotal->setNetSumTotal($netSumTotal);
        $itemsTotal->setTotal($total);
        $itemsTotal->setVatTotal($vatTotal);

        return $itemsTotal;
    }
}