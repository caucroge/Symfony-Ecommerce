<?php

namespace App\Form\Type;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddressChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addressLivraison', EntityType::class, [
                'class' => Address::class,
                'query_builder' => function (AddressRepository $addressRepository) {
                    return $addressRepository->getQueryBuilderfindAllByUser();
                },
                'choice_label' => function ($address) {
                    return $address->toString();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
