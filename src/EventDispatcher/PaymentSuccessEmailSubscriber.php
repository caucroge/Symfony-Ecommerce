<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\PaymentSuccessEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class PaymentSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger, protected MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'payment.success' => 'sendEmail',
        ];
    }

    public function sendEmail(PaymentSuccessEvent $paymentSuccessEvent)
    {
        $fullName = $paymentSuccessEvent->getCommande()->getFullName();
        $numberCommande = $paymentSuccessEvent->getCommande()->getId();

        $email = new Email();
        $fullName = $paymentSuccessEvent->getCommande()->getFullName();
        $commandeId = $paymentSuccessEvent->getCommande()->getId();

        $email
            ->from(new Address("contact@ecommerce.com", "Ecommerce"))
            ->to("admin@ecommerce.com")
            ->subject("Validation de commande")
            ->text("$fullName à valider la commande n° $commandeId");

        $this->mailer->send($email);
    }
}
