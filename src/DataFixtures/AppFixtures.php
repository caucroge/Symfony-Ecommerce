<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 100; $i++)
        {
            $product = new Product;
            $product->setName("Produit n°$i")
                    ->setPrice(mt_rand(100,200))
                    ->setSlug("Produit-n-$i");
            $manager->persist($product);
        }
        
        $manager->flush();
    }
}
