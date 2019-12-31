<?php

declare(strict_types=1);

namespace App\Infrastructure\Promotion\Notification;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewMembershipCardNotificationHandler implements MessageHandlerInterface
{
    /** @var MailerInterface */
    private $mailer;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NewMembershipCardNotification $notification): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@sweetspot.io')
            ->to($notification->getMembershipCard()->getPlayer()->getExistingEmail())
            ->subject($notification->getMembershipCard()->getMembership()->getName())
            ->htmlTemplate('email/promotion/se/new-membership-card.html.twig')
            ->context([
                'player' => $notification->getMembershipCard()->getPlayer(),
                'membership' => $notification->getMembershipCard()->getMembership(),
                'club' => $notification->getMembershipCard()->getClub(),
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return;
        }
    }
}
