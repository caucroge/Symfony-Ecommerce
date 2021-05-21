<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier/add/{id}', name: 'panier_add', requirements: ['id' => "\d+"])]
    public function add($id, Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panier = $request->getSession()->get('panier', []);

        if (array_key_exists($id, $panier)) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $request->getSession()->set('panier', $panier);

        return $this->redirectToRoute('product_read_slug', [
            'slug' => $product->getSlug(),
        ]);
    }
}
