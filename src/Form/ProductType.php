<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'mainPicture',
                UrlType::class,
                [
                    "label" => "Image du produit",
                    "attr" => ["placeholder" => "Url d'une image"]
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $formEvent) {

                $form = $formEvent->getForm();

                /**@var Product */
                $product = $formEvent->getData();

                if ($product->getId() === null) {
                    $form
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
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
