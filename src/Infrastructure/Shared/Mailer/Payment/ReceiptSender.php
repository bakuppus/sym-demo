<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer\Payment;

use App\Domain\Payment\Core\PaymentInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ReceiptSender
{
    /** @var MailerInterface */
    private $mailer;

    /** @var ReceiptGenerator */
    private $receiptGenerator;

    private $translator;

    public function __construct(
        MailerInterface $mailer,
        ReceiptGenerator $receiptGenerator,
        TranslatorInterface $translator
    ) {
        $this->mailer = $mailer;
        $this->receiptGenerator = $receiptGenerator;
        $this->translator = $translator;
    }

    /**
     * @param PaymentInterface $payment
     *
     * @throws TransportExceptionInterface
     */
    public function send(PaymentInterface $payment)
    {
        $email = new TemplatedEmail();
        $to = $payment->getOrder()->getCustomer()->getExistingEmail();
        $subject = $this->translator->trans('receipt.title', [], 'emails');

        $receipt = $this->receiptGenerator->getReceipt($payment);
        $email
            ->from('no-reply@sweetspot.io')
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('email/payment/receipt.html.twig')
            ->context(['receipt' => $receipt]);

        $this->mailer->send($email);
    }
}