<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

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
    public function create(
        Request $request,
        SluggerInterface $string,
        EntityManagerInterface $em
    ) {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $product->setSlug(strtolower($string->slug($product->getName())));
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_read_slug', [
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render(
            "product/create.html.twig",
            [
                'formView' => $formView
            ]
        );
    }

    #[Route('/product/update/{id}', name: "product_update_id")]
    public function updateId(
        $id,
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $string,
        ValidatorInterface $validator
    ) {
        $product = new Product();
        $result = $validator->validate($product);

        $nbrErr = $result->count();
        if ($nbrErr > 0) {
            dd("Il y a $nbrErr erreur(s) de validation !", $result);
        }
        dd("Pas d'erreurs de validation");

        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $product->setSlug(strtolower($string->slug($product->getName())));
            dd($product);
            $em->flush();

            return $this->redirectToRoute('product_read_slug', [
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/update.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }
}
