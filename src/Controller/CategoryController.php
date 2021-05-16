<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_slug')]
    public function categorySlug($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(
            [
                'slug' => $slug
            ]
        );

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée \"$slug\" n'existe pas !");
        }

        return $this->render(
            'category/categorySlug.html.twig',
            [
                'category' => $category,
            ]
        );
    }
}
