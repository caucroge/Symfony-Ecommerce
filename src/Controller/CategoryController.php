<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function renderMenuList()
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('shared/_categoryMenu.html.twig', [
            'categories' => $categories
        ]);
    }

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
    public function create(
        SluggerInterface $string,
        EntityManagerInterface $em,
        Request $request
    ) {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($string->slug($category->getName())));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        $formView = $form->createView();

        return $this->render(
            'category/create.html.twig',
            [
                'formView' => $formView
            ]
        );
    }

    #[Route('/category/update/{id}', name: "category_update_id")]
    public function update(
        $id,
        CategoryRepository $categoryRepository,
        Request $request,
        SluggerInterface $string,
        EntityManagerInterface $em
    ) {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($string->slug($category->getName())));

            $em->flush();

            return $this->redirectToRoute('index');
        }

        $formView = $form->createView();

        return $this->render(
            'category/update.html.twig',
            [
                'category' => $category,
                'formView' => $formView
            ]
        );
    }
}
