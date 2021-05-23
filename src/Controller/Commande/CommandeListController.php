<?php

namespace App\Controller\Commande;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeListController extends AbstractController
{
    #[Route('/commande', name: 'commande_index')]
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        if (!$user) {
            $this->redirectToRoute('index');
        }

        return $this->render('commande/index.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }
}
