<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route("/", name: "index", priority: -1)]
    public function index(ProductRepository $productRepository, Request $request)
    {
        // Mise en session de la route courante
        $session = $request->getSession();
        $session->set('route', 'index');

        $products = $productRepository->findBy([], [], 3);

        return $this->render('index.html.twig', [
            'products' => $products
        ]);
    }
}
