<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route("/", name: "homepageIndex")]
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], [], 3);

        return $this->render('homepage/index.html.twig', [
            'products' => $products
        ]);
    }
}
