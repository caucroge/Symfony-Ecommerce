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

    #[Route('/category/create', name: "category_create")]
    public function create()
    {
        return $this->render(
            'category/create.html.twig'
        );
    }

    #[Route('/category/update/{id}', name: "category_update_id")]
    public function update($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render(
            'category/update.html.twig',
            [
                'category' => $category
            ]
        );
    }
}
