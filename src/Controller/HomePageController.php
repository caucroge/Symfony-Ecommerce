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
    public function index(EntityManagerInterface $em)
    {
        // $product = new Product;
        // $product->setName("Chaise en plasique")
        //         ->setPrice(1500)
        //         ->setSlug("Chaise-en-plastique");
        // $em->persist($product);

        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find(3);
        // $product->setPrice(2500);
        // $em->flush();

        $em->remove($product);
        $em->flush();

        return $this->render('homepage/index.html.twig');
    }
}