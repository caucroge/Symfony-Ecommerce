<?php

namespace App\Controller;

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
        SessionInterface $session
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panier = $session->get('panier', []);

        if (array_key_exists($id, $panier)) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        $this->addFlash('success', "Le produit : {$product->getName()} à été ajouter dans votre panier.");

        return $this->redirectToRoute('product_read_slug', [
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read(SessionInterface $session, ProductRepository $productRepository)
    {
        $items = [];
        $allSum = 0;

        foreach ($session->get('panier', []) as $id => $quantity) {

            $product = $productRepository->find($id);
            $sum = $product->getPrice() * $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'sum' => $sum,
            ];
            $allSum += $sum;
        }

        return $this->render('panier/read.html.twig', [
            'items' => $items,
            'allSum' => $allSum,
        ]);
    }
}
