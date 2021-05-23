<?php

namespace App\Controller\Commande;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeListController extends AbstractController
{
    #[Route('/commande', name: 'commande_index')]
    // /**
    //  * @Security("is_granted('ROLE_USER')", message="Vous devez être connecter pour accéder à vos commandes")
    //  */
    /**
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('commande/index.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }
}
