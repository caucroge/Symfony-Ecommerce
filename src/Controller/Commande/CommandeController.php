<?php

namespace App\Controller\Commande;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Service\PanierService;
use App\Service\StripeService;
use App\Form\Type\CommandeType;
use App\Form\Type\AddressChoiceType;
use App\Repository\AddressRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
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
            $addressLivraison = $this->addressRepository->find($addressLivraisonId);

            // Addresse de livraison invalide
            if (!$addressLivraison) {

                $this->addFlash("danger", "L'addresse de livraison est invalide!");
                return $this->redirectToRoute("commande_addresse_livraison");
            }

            // Addresse de livraison valide
            $session->set('adresseLivraison', $addressLivraison->toArray());
            return $this->redirectToRoute('commande_payment');
        }

        return $this->render(
            "commande/addressLivraison.html.twig",
            [
                'formView' => $form->createView(),
                'lignePaniers' => $this->panierService->getLignePaniers(),
                'totalPanier' => $this->panierService->getTotalPanier()
            ]
        );
    }

    #[Route('/commande/payment', name: 'commande_payment')]
    public function payment(Request $request, StripeService $stripeService)
    {
        // Mise en session de la route courante
        $session = $request->getSession();
        $session->set('route', 'commande_payment');

        $totalPanier = $this->panierService->getTotalPanier();
        $paymentIntent = $stripeService->getPaymentIntent($totalPanier * 100);

        return $this->render("commande/payment.html.twig", [
            'address' => $session->get('adresseLivraison'),
            'lignePaniers' => $this->panierService->getLignePaniers(),
            'totalPanier' => $totalPanier,
            'stripeSecretKey' => $paymentIntent->client_secret,
            'stripePublicKey' => $stripeService->getPublicKey(),
        ]);
    }

    #[Route('/commande/create', name: 'commande_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $commande = new Commande();
        $commande
            ->setCustomer($this->getUser())
            ->setFullName($this->getUser()->getFullName())
            ->setTotal($this->panierService->getTotalPanier())
            ->setStatus(Commande::STATUS_PAID)
            ->setAddressDelivery($request->getSession()->get('adresseLivraison'))
            ->setCreateAt(new \DateTime());
        $em->persist($commande);

        $lignePaniers = $this->panierService->getLignePaniers();
        foreach ($lignePaniers as $lignePanier) {

            $ligneCommande = new LigneCommande();
            $ligneCommande
                ->setCommande($commande)
                ->setProduct($lignePanier->getProduct())
                ->setName($lignePanier->getName())
                ->setPrice($lignePanier->getPrice())
                ->setQuantity($lignePanier->getQuantity())
                ->setTotal($lignePanier->getTotal());
            $em->persist($ligneCommande);
        }

        $em->flush();

        $request->getSession()->remove('adresseLivraison');
        $this->panierService->removeAll($this->getUser());

        $this->addFlash('success', 'La commande à été payé et confirmé.');
        return $this->redirectToRoute("commande_list");
    }

    #[Route('/commande/list', name: 'commande_list')]
    public function list()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('commande/list.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }
}
