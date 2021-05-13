<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use Liior\Faker\Prices;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category
                ->setName($faker->word())
                ->setSlug(
                    strtolower($this->slugger->slug($category->getName()))
                );
            $manager->persist($category);

            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $product = new Product();
                $product
                    ->setName($faker->productName())
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(
                        strtolower($this->slugger->slug($product->getName()))
                    )
                    ->setCategory($category);
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
