<?php

namespace App\Controller;

use App\Handler\PanierHandler;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier/add/{id}', name: 'panier_add', requirements: ['id' => "\d+"])]
    public function add(
        $id,
        ProductRepository $productRepository,
        PanierHandler $panierHandler,
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panierHandler->add($id);
        $this->addFlash('success', "Le produit : {$product->getName()} à été ajouter dans votre panier.");

        return $this->redirectToRoute('product_read_slug', [
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read(PanierHandler $panierHandler)
    {
        return $this->render('panier/read.html.twig', [
            'items' => $panierHandler->getItems(),
            'allSum' => $panierHandler->getAllSum()
        ]);
    }
}
