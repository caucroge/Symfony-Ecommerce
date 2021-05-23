<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Nom complet'
                ]
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Addresse complète',
                'attr' => [
                    'placeholder' => 'Addresse'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'label' => "Code postal",
                'attr' => [
                    'placeholder' => "Code postal"
                ]
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr' => [
                    'placeholder' => "Ville"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
