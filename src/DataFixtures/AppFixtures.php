<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Panier;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\LignePanier;
use App\Entity\LigneCommande;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Monolog\Processor\ConsoleCommandProcessor;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        // Table User
        $admin = new User();
        $admin->setEmail("admin@gmail.com");
        $admin->setFullName($faker->name());
        $admin->setPassword($this->encoder->encodePassword($admin, "admin"));
        $admin->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);

        $users = [];
        for ($i = 0; $i < 5; $i++) {

            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setFullName($faker->name());
            $user->setPassword($this->encoder->encodePassword($user, "password"));

            $users[] = $user;
            $manager->persist($user);
        }

        // Table Category
        $categories = [];
        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category
                ->setName($faker->word())
                ->setSlug(
                    strtolower($this->slugger->slug($category->getName()))
                );

            $categories[] = $category;
            $manager->persist($category);
        }

        // Table Product
        $products = [];
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product
                ->setName($faker->productName())
                ->setPrice($faker->price(4000, 20000))
                ->setSlug(
                    strtolower($this->slugger->slug($product->getName()))
                )
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400, true));

            // Relation ManyToOne Product-->Category 
            $product
                ->setCategory($faker->randomElement($categories));

            $products[] = $product;
            $manager->persist($product);
        }

        // Table LignePanier
        // Relation ManyToOne LignePanier --> User
        foreach ($users as $user) {

            // Relation ManyToOne LigneCommande --> Product
            $productElements = $faker->randomElements($products, mt_rand(3, 10));
            foreach ($productElements as $product) {

                $lignePanier = new LignePanier();
                $lignePanier
                    ->setUser($user)
                    ->setProduct($product)
                    ->setName($product->getName())
                    ->setPrice($product->getPrice())
                    ->setQuantity(mt_rand(1, 5))
                    ->setTotal($product->getPrice() * $lignePanier->getQuantity());

                $manager->persist($lignePanier);
            }
        }

        // Table Commande
        $commandes = [];
        for ($i = 0; $i < mt_rand(20, 40); $i++) {

            $commande = new Commande();
            $commande
                ->setCreateAt($faker->dateTime())
                ->setFullName($faker->name())
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setTotal($faker->price(6000, 60000));

            if ($faker->boolean(90)) {
                $commande->setStatus(Commande::STATUS_PAID);
            }

            // Relation ManyToOne Commande --> User
            $commande->setCustomer($faker->randomElement($users));

            $commandes[] = $commande;
            $manager->persist($commande);
        }

        // Table LigneCommande
        // Relation ManyToOne LigneCommande --> Commande
        foreach ($commandes as $commande) {

            // Relation ManyToOne LigneCommande --> Product
            $productElements = $faker->randomElements($products, mt_rand(3, 10));
            foreach ($productElements as $product) {

                $ligneCommande = new LigneCommande();
                $ligneCommande
                    ->setCommande($commande)
                    ->setProduct($product)
                    ->setName($product->getName())
                    ->setPrice($product->getPrice())
                    ->setQuantity(mt_rand(1, 5))
                    ->setTotal($product->getPrice() * $lignePanier->getQuantity());

                $manager->persist($ligneCommande);
            }
        }

        $manager->flush();
    }
}
