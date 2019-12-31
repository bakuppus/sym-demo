<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Model;

final class Receipt
{
    /** @var Info */
    private $info;

    /** @var Meta[] */
    private $meta;

    /** @var Item[] */
    private $items;

    /** @var Total */
    private $total;

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function setInfo(Info $info): void
    {
        $this->info = $info;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getTotal(): Total
    {
        return $this->total;
    }

    public function setTotal(Total $total): void
    {
        $this->total = $total;
    }
}