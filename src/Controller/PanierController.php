<?php

namespace App\Controller;

use App\Handler\PanierHandler;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier/read', name: 'panier_read')]
    public function read(PanierHandler $panierHandler)
    {
        return $this->render('panier/read.html.twig', [
            'items' => $panierHandler->getItems(),
            'allSum' => $panierHandler->getAllSum()
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add_id', requirements: ['id' => "\d+"])]
    public function add(
        $id,
        ProductRepository $productRepository,
        PanierHandler $panierHandler,
        Request $request,
    ): Response {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panierHandler->add($id);
        $this->addFlash('success', "Le produit : {$product->getName()} à été ajouter dans votre panier.");

        if ($request->query->get('returnToPanier')) {

            return $this->redirectToRoute('panier_read');
        } else {

            return $this->redirectToRoute('product_read_slug', [
                'slug' => $product->getSlug(),
            ]);
        }
    }

    #[Route('/panier/delete/{id}', name: 'panier_delete_id', requirements: ["id" => "\d+"])]
    public function delete($id, ProductRepository $productRepository, PanierHandler $panierHandler)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panierHandler->remove($id);
        $this->addFlash("success", "Le produit {$product->getName()} à bien été retiré du panier");

        return $this->redirectToRoute("panier_read");
    }

    #[Route('/panier/decrement/{id}', name: 'panier_decrement_id', requirements: ["id" => "\d+"])]
    public function decrement($id, ProductRepository $productRepository, PanierHandler $panierHandler)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $panierHandler->decrement($id);
        $this->addFlash('warning', "Un produit : {$product->getName()} à été enlevé du panier !");

        return $this->redirectToRoute("panier_read");
    }
}
