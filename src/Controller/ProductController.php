<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom du produit',
                    'attr' => ['placeholder' => 'Nom du produit']
                ]
            )
            ->add(
                'shortDescription',
                TextareaType::class,
                [
                    'label' => 'Description courte',
                    'attr' => ["placeholder" => "Description courte mais parlante pour l'utilisateur"]
                ]
            )
            ->add(
                'price',
                MoneyType::class,
                [
                    "label" => "Prix du produit",
                    "attr" => ["placeholder" => "Prix du produit"]
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    "label" => "Catégorie",
                    "placeholder" => "Choisir une catégorie",
                    "class" => Category::class,
                    "choice_label" => function (Category $category) {
                        return strtoupper($category->getName());
                    }

                ]
            );

        $form = $builder->getForm();
        $formView = $form->createView();

        return $this->render(
            "product/create.html.twig",
            [
                'formView' => $formView
            ]
        );
    }
}
