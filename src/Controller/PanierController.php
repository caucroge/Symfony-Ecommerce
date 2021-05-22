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
    protected $productRepository;
    protected $panierHandler;

    public function __construct(ProductRepository $productRepository, PanierHandler $panierHandler)
    {
        $this->productRepository = $productRepository;
        $this->panierHandler = $panierHandler;
    }

    #[Route('/panier/read', name: 'panier_read')]
    public function read()
    {
        return $this->render('panier/read.html.twig', [
            'items' => $this->panierHandler->getItems(),
            'allSum' => $this->panierHandler->getAllSum()
        ]);
    }

    #[Route('/panier/add/{id}', name: 'panier_add_id', requirements: ['id' => "\d+"])]
    public function add(
        $id,
        Request $request,
    ): Response {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $this->panierHandler->add($id);
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
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $this->panierHandler->remove($id);
        $this->addFlash("success", "Le produit {$product->getName()} à bien été retiré du panier");

        return $this->redirectToRoute("panier_read");
    }

    #[Route('/panier/decrement/{id}', name: 'panier_decrement_id', requirements: ["id" => "\d+"])]
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit '$id' n'existe pas ");
        }

        $this->panierHandler->decrement($id);
        $this->addFlash('warning', "Un produit : {$product->getName()} à été enlevé du panier !");

        return $this->redirectToRoute("panier_read");
    }
}
