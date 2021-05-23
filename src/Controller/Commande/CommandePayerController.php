<?php

namespace App\Controller\Commande;

use App\Handler\PanierHandler;
use App\Form\PanierConfirmationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CommandePayerController extends AbstractController
{
    protected $formFactory;
    protected $panierHandler;

    public function __construct(FormFactoryInterface $formFactory, PanierHandler $panierHandler)
    {
        $this->formFactory = $formFactory;
        $this->panierHandler = $panierHandler;
    }

    #[Route('/commande/payer', name: 'commande_payer')]
    public function payer(Request $request)
    {
        $form = $this->formFactory->create(PanierConfirmationType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash("warning", "Vous devez confirmer la commande !");
            return $this->redirectToRoute('panier_read');
        }

        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour confirmer une commande");
        }

        $nbrArticle = $this->panierHandler->getCountItems();
        if ($nbrArticle === 0) {
            $this->addFlash("warning", "Votre panier est vide !");
        }

        return $this->redirectToRoute('panier_read');
    }
}
