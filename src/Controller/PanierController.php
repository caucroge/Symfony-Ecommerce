<?php

namespace App\Controller;

use App\Service\PanierService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    protected string $route;

    public function __construct(
        private PanierService $panierService
    ) {
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read(Request $request)
    {
        // Mise en session de la route courante
        $session = $request->getSession();
        $session->set('route', 'panier_read');
        $session->remove('routeParameterName');
        $session->remove('routeParameterValue');

        return $this->render('panier/read.html.twig', [
            'lignePaniers' => $this->panierService->getLignePaniers(),
            'totalPanier' => $this->panierService->getTotalPanier()
        ]);
    }

    #[Route('/panier/increment/produit/{id}', name: 'panier_increment_produit_id', requirements: ['id' => "\d+"])]
    public function incrementProduit(int $id, Request $request): Response
    {
        try {
            $product = $this->panierService->incrementProduit($id);
            $this->addFlash('success', "Un produit : {$product->getName()} à été ajouter dans votre panier.");
        } catch (EntityNotFoundException $error) {
            $this->addFlash('danger', $error->getMessage());
        } finally {
            return $this->redirection($request);
        }
    }

    #[Route('/panier/decrement/produit/{id}', name: 'panier_decrement_produit_id', requirements: ["id" => "\d+"])]
    public function decrementProduit($id, Request $request)
    {
        try {
            $product = $this->panierService->decrementProduit($id);
            $this->addFlash('warning', "Un produit : {$product->getName()} à été enlevé du panier !");
        } catch (EntityNotFoundException $error) {
            $this->addFlash('danger', $error->getMessage());
        } finally {
            return $this->redirection($request);
        }
    }

    #[Route('/panier/delete/ligne/{id}', name: 'panier_delete_ligne_id', requirements: ["id" => "\d+"])]
    public function deleteLigne($id, Request $request)
    {
        try {
            $product = $this->panierService->remove($id);
            $this->addFlash("warning", "La ligne du produit {$product->getName()} à bien été retiré du panier");
        } catch (EntityNotFoundException $error) {
            $this->addFlash("danger", $error->getMessage());
        } finally {
            return $this->redirection($request);
        }
    }

    private function redirection(Request $request)
    {
        $route = $request->getSession()->get('route');
        $parameterName = $request->getSession()->get('routeParameterName') ?? null;
        $parameterValue = $request->getSession()->get('routeParameterValue') ?? null;

        return $this->redirectToRoute($route, [
            $parameterName => $parameterValue
        ]);
    }
}
