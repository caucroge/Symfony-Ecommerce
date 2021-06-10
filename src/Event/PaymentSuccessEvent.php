<?php

namespace App\Event;

use App\Entity\Commande;
use Symfony\Contracts\EventDispatcher\Event;

class PaymentSuccessEvent extends Event
{
    public function __construct(private Commande $commande)
    {
    }

    // Getters et setters
    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }
}
