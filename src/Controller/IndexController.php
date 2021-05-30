<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route("/", name: "index", priority: -1)]
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 3);

        return $this->render('index.html.twig', [
            'products' => $products
        ]);
    }
}
