<?php

namespace App\Domain\Order\Component;

use Doctrine\Common\Collections\Collection;

interface OrderInterface/* extends AdjustableInterface*/
{
    /**
     * @return Collection|OrderItemInterface[]
     */
    public function getItems(): Collection;

    public function addItem(OrderItemInterface $item): void;

    public function removeItem(OrderItemInterface $item): void;

    public function hasItem(OrderItemInterface $item): bool;

    public function countItems(): int;

    public function clearItems(): void;

    public function getItemsTotal(): int;

    public function recalculateItemsTotal(): void;

    public function isEmpty(): bool;

    public function getNumber(): ?string;

    public function setNumber(?string $number): void;

    public function getNotes(): ?string;

    public function setNotes(?string $notes): void;

    public function getTotal(): int;

    public function getTotalQuantity(): int;

    public function getState(): string;

    public function setState(string $state): void;

    public function getToken(): ?string;

    public function setToken(?string $tokenValue): void;
}
