<?php

namespace App\EventDispatcher;

use App\Entity\LigneCommande;
use Psr\Log\LoggerInterface;
use App\Event\PaymentSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PaymentSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected MailerInterface $mailer,
        protected Security $security,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'payment.success' => 'sendEmail',
        ];
    }

    public function sendEmail(PaymentSuccessEvent $paymentSuccessEvent)
    {
        $commande = $paymentSuccessEvent->getCommande();
        $commandeLignes = $commande->getLigneCommandes();

        // Construction de l'objet Mail
        $email = new TemplatedEmail();
        $email
            ->from(new Address("no-replyt@ecommerce.com", "Ecommerce"))
            ->htmlTemplate('email/paymentEmail.html.twig')
            ->context(
                [
                    'commande' => $commande,
                    'commandeLignes' => $commandeLignes,
                ]
            );

        // Envoie email à notre service Commercial
        $email
            ->to("commercial@ecommerce.com")
            ->subject("Validation de commande");
        $this->mailer->send($email);

        // Envoie eamil de confirmation pour le client
        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $email
            ->to($user->getEmail())
            ->subject("Merci de votre commande");
        $this->mailer->send($email);
    }
}
