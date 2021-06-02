<?php

namespace App\Controller\Commande;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Commande;
use App\Service\PanierService;
use App\Form\Type\CommandeType;
use App\Form\Type\AddressChoiceType;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    // Attributs
    protected PanierService $panierService;
    protected AddressRepository $addressRepository;
    protected User $user;

    // Constroller
    public function __construct(PanierService $panierService, AddressRepository $addressRepository)
    {
        $this->panierService = $panierService;
        $this->addressRepository = $addressRepository;
    }

    // Actions
    #[Route('/commande/adresse/livraison', name: 'commande_addresse_livraison')]
    public function adresseLivraison(Request $request)
    {
        // Mise en session de la route courante
        $session = $request->getSession();
        $session->set('route', 'commande_addresse_livraison');
        $session->remove('routeParameterName');
        $session->remove('routeParameterValue');

        // Gestion du formulaire
        $form = $this->createForm(AddressChoiceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $addressLivraisonId = $request->request->get('address_choice')['addressLivraison'];

            // Redirection Route
            return $this->redirectToRoute('commande_mode_paiement', [
                'addressLivraisonId' => $addressLivraisonId
            ]);
        }

        // Page en réponse
        return $this->render(
            "commande/addressLivraison.html.twig",
            [
                'formView' => $form->createView(),
                'lignePaniers' => $this->panierService->getLignePaniers(),
                'totalPanier' => $this->panierService->getTotalPanier()
            ]
        );
    }

    #[Route('/commande/modePaiement/{addressLivraisonId}', name: 'commande_mode_paiement')]
    public function modePaiement(int $addressLivraisonId, Request $request)
    {
        // Mise en session de la route courante
        $session = $request->getSession();
        $session->set('route', 'commande_mode_paiement');
        $session->set('routeParameterName', 'addressLivraisonId');
        $session->set('routeParameterValue', $addressLivraisonId);

        // Vérification de l'existance de l'adresse dans la table Address
        $addressLivraison = $this->addressRepository->find($addressLivraisonId);
        if (!$addressLivraison) {

            $this->addFlash("danger", "L'addresse de livraison est invalide!");
            return $this->redirectToRoute("commande_addresse_livraison");
        }

        return $this->render("commande/modePaiement.html.twig", [
            'address' => $addressLivraison->toString(),
            'lignePaniers' => $this->panierService->getLignePaniers(),
            'totalPanier' => $this->panierService->getTotalPanier()
        ]);
    }


    #[Route('/commande/create', name: 'commande_create')]
    public function create(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            return $this->redirectToRoute('commande_pay');
        }

        return $this->render(
            "commande/create.html.twig",
            [
                'formView' => $form->createView(),
                'lignePaniers' => $this->panierService->getLignePaniers(),
                'totalPanier' => $this->panierService->getTotalPanier()
            ]
        );
    }

    #[Route('/commande/pay', name: 'commande_pay')]
    public function pay(Request $request)
    {
        return $this->render('commande/pay.html.twig');
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
