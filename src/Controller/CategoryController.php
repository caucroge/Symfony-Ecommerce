<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/read/{slug}', name: 'category_read_slug')]
    public function readSlug(
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
