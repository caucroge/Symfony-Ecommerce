<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'show_category_products_slug')]
    public function showCategoryProductsSlug(
        $slug,
        CategoryRepository $categoryRepository,
    ): Response {
        $category = $categoryRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée \"$slug\" n'existe pas !");
        }

        return $this->render(
            'category/showCategoryProductsSlug.html.twig',
            [
                'category' => $category
            ]
        );
    }
}
