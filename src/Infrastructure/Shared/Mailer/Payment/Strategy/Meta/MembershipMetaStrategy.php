<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment\Strategy\Meta;

use App\Domain\Order\OrderMembership;
use App\Domain\Payment\Core\PaymentInterface;
use App\Infrastructure\Shared\Mailer\Payment\Model\Meta;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MembershipMetaStrategy implements MetaStrategyInterface
{
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getMeta(PaymentInterface $payment): array
    {
        /** @var OrderMembership $order */
        $order = $payment->getOrder();

        $clubMeta = new Meta();
        $clubTranslation = $this->translator->trans('receipt.club', [], 'emails');
        $clubMeta->setKey($clubTranslation);
        $clubMeta->setValue($order->getClub()->getName());

        $membershipMeta = new Meta();
        $membershipTranslation = $this->translator->trans('receipt.membership', [], 'emails');
        $membershipMeta->setKey($membershipTranslation);
        $membershipMeta->setValue($order->getMembership()->getName());

        $meta = [
            $clubMeta,
            $membershipMeta,
        ];

        return $meta;
    }

    public function supports(PaymentInterface $payment): bool
    {
        $order = $payment->getOrder();

        return $order instanceof OrderMembership;
    }
}