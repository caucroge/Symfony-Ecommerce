<?php

namespace App\Controller\Commande;

use App\Entity\Commande;
use App\Form\Type\CommandeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/create', name: 'commande_create')]
    public function create(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        return $this->render(
            "commande/create.html.twig",
            [
                'formView' => $form->createView()
            ]
        );
    }

    #[Route('/commande/payer', name: 'commande_payer')]
    public function payer(Request $request)
    {
        return $this->redirectToRoute('commande_create');
    }

    #[Route('/commande/list', name: 'commande_list')]
    /**
     * @IsGranted("ROLE_USER")
     */
    public function list()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('commande/list.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }
}
