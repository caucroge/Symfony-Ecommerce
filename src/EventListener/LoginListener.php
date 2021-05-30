<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Service\PanierService;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;
    private $panierService;

    public function __construct(EntityManagerInterface $em, PanierService $panierService)
    {
        $this->em = $em;
        $this->panierService = $panierService;
    }

    public function onSecurityInteractivelogin(InteractiveLoginEvent $event)
    {
        /**
         * @var User $user
         */
        $user = $event->getAuthenticationToken()->getUser();
        $this->panierService->successLogin($user);
    }
}
