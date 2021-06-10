<?php

namespace App\EventDispatcher;

use App\Event\PaymentSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(private LoggerInterface $logger)
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
        $this->logger->info("Email envoyé à $fullName pour la commande n° $numberCommande");
    }
}
