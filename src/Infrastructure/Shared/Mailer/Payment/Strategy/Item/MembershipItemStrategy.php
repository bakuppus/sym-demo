<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Item;

use App\Domain\Order\Item\OrderItemMembershipCard;
use App\Domain\Order\OrderMembership;
use App\Domain\Payment\Core\PaymentInterface;
use App\Domain\Promotion\Membership;
use App\Infrastructure\Shared\Mailer\Payment\Model\Item;
use App\Infrastructure\Shared\Utils\MoneyConverter;

final class MembershipItemStrategy implements ItemStrategyInterface
{
    use MoneyConverter;

    public function getItems(PaymentInterface $payment): array
    {
        /** @var OrderMembership $order */
        $order = $payment->getOrder();

        /** @var OrderItemMembershipCard[] $membershipCardItems */
        $membershipCardItems = $order->getItems();

        $items = [];

        foreach ($membershipCardItems as $membershipCardItem) {
            $membershipCard = $membershipCardItem->getMembershipCard();

            /** @var Membership $membership */
            $membership = $membershipCard->getMembership();

            $fees = $membership->getFees();

            foreach ($fees as $fee) {
                $feeUnit = $fee->getFeeUnit();
                $price = $this->centsToMoney($fee->getPrice());
                $vatPercentage = $fee->getVat();
                $vat = $this->calculateVat($price, $vatPercentage);
                $netSum = $price - $vat;

                $item = new Item();
                $item->setProduct($feeUnit->getName());
                $item->setVatPercentage($vatPercentage);
                $item->setTotal($price);
                $item->setVat($vat);
                $item->setNetSum($netSum);

                $items[] = $item;
            }
        }

        return $items;
    }

    public function supports(PaymentInterface $payment): bool
    {
        $order = $payment->getOrder();

        return $order instanceof OrderMembership;
    }

    private function calculateVat(float $price, float $vatPercentage): float
    {
        return ($price * $vatPercentage) / 100;
    }
}