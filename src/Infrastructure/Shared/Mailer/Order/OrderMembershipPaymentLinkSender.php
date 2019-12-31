<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Order;

use App\Domain\Order\Core\OrderInterface;
use App\Domain\Order\OrderMembership;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

final class OrderMembershipPaymentLinkSender implements OrderPaymentLinkSenderInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var OrderPaymentLinkGenerator */
    private $linkGenerator;

    public function __construct(MailerInterface $mailer, OrderPaymentLinkGenerator $linkGenerator)
    {
        $this->mailer = $mailer;
        $this->linkGenerator = $linkGenerator;
    }

    /**
     * @param OrderMembership|OrderInterface $order
     *
     * @throws TransportExceptionInterface
     */
    public function send(OrderInterface $order): void
    {
        if (false === $order instanceof OrderMembership) {
            return;
        }

        $link = $this->linkGenerator->generate($order);

        $email = new TemplatedEmail();
        $email
            ->from('no-reply@sweetspot.io')
            ->to($order->getCustomer()->getExistingEmail())
            ->subject($order->getMembership()->getName())
            ->htmlTemplate('email/order/en/payment-link.html.twig')
            ->context(
                [
                    'player' => $order->getCustomer(),
                    'membership' => $order->getMembership(),
                    'club' => $order->getClub(),
                    'paymentLink' => $link,
                ]
            );

        $this->mailer->send($email);
    }
}
