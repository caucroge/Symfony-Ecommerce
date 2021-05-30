<?php

namespace App\Controller;

use App\Service\PanierService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    protected $productRepository;
    protected $panierService;

    public function __construct(ProductRepository $productRepository, PanierService $panierService)
    {
        $this->productRepository = $productRepository;
        $this->panierService = $panierService;
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read()
    {
        return $this->render('panier/read.html.twig', [
            'lignePaniers' => $this->panierService->getLignePaniers(),
            'totalPanier' => $this->panierService->getTotalPanier()
        ]);
    }

    #[Route('/panier/increment/produit/{id}', name: 'panier_increment_produit_id', requirements: ['id' => "\d+"])]
    public function incrementProduit($id): Response
    {
        try {
            $product = $this->panierService->incrementProduit($id);
            $this->addFlash('success', "Un produit : {$product->getName()} à été ajouter dans votre panier.");
        } catch (EntityNotFoundException $error) {
            $this->addFlash('danger', $error->getMessage());
        } finally {
            return $this->redirectToRoute('panier_read');
        }
    }

    #[Route('/panier/decrement/produit/{id}', name: 'panier_decrement_produit_id', requirements: ["id" => "\d+"])]
    public function decrementProduit($id)
    {
        try {
            $product = $this->panierService->decrementProduit($id);
            $this->addFlash('warning', "Un produit : {$product->getName()} à été enlevé du panier !");
        } catch (EntityNotFoundException $error) {
            $this->addFlash('danger', $error->getMessage());
        } finally {
            return $this->redirectToRoute("panier_read");
        }
    }

    #[Route('/panier/delete/ligne/{id}', name: 'panier_delete_ligne_id', requirements: ["id" => "\d+"])]
    public function deleteLigne($id)
    {
        try {
            $product = $this->panierService->remove($id);
            $this->addFlash("warning", "La ligne du produit {$product->getName()} à bien été retiré du panier");
        } catch (EntityNotFoundException $error) {
            $this->addFlash("danger", $error->getMessage());
        } finally {
            return $this->redirectToRoute("panier_read");
        }
    }
}
