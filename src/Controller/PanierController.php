<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        /**@var FlashBag */
        $flashBag = $session->getBag('flashes');
        $flashBag->add('success', "Tout s'est bien passé.");
        $flashBag->add('warning', "Attention !");

        return $this->redirectToRoute('product_read_slug', [
            'slug' => $product->getSlug(),
        ]);
    }
}
