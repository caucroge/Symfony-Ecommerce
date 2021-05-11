<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route("/", name: "homepageIndex")]
    public function index(ProductRepository $productRepository)
    {
        $product = $productRepository->find(1);
        dump($product);
        return $this->render('homepage/index.html.twig');
    }
}