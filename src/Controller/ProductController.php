<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/read/{slug}', name: 'product_read_slug')]
    public function readSlug(
        $slug,
        ProductRepository $productRepository,
    ): Response {
        $product = $productRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );

        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé \"$slug\" n'existe pas !");
        }

        return $this->render(
            'product/showProductSlug.html.twig',
            [
                'product' => $product
            ]
        );
    }

    #[Route('/product/create', name: "product_create")]
    public function create(FormFactoryInterface $factory)
    {
        $builder = $factory->createBuilder();

        $builder
            ->add('name')
            ->add('shortDescription')
            ->add('price')
            ->add('category');

        $form = $builder->getForm();
        $formView = $form->createView();

        return $this->render(
            "product/create.html.twig",
            [
                'formview' => $formView
            ]
        );
    }
}
