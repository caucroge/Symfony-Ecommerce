<?php

namespace App\Controller;

use App\Service\PanierService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class PanierController extends AbstractController
{
    #[Route('/panier/add/{id}', name: 'panier_add', requirements: ['id' => "\d+"])]
    public function add(
        $id,
        ProductRepository $productRepository,
        PanierService $panierService,
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panierService->add($id);
        $this->addFlash('success', "Le produit : {$product->getName()} à été ajouter dans votre panier.");

        return $this->redirectToRoute('product_read_slug', [
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read(PanierService $panierService)
    {
        $items = $panierService->getItems();
        $allSum = $panierService->getAllSum();

        return $this->render('panier/read.html.twig', [
            'items' => $items,
            'allSum' => $allSum,
        ]);
    }
}
