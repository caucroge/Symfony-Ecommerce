<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\User;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

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

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category
                ->setName($faker->word())
                ->setSlug(
                    strtolower($this->slugger->slug($category->getName()))
                );
            $manager->persist($category);

            for ($j = 0; $j < mt_rand(2, 10); $j++) {
                $product = new Product();
                $product
                    ->setName($faker->productName())
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug(
                        strtolower($this->slugger->slug($product->getName()))
                    )
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));
                $manager->persist($product);
            }
        }

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

        for ($i = 0; $i < mt_rand(20, 40); $i++) {
            $commande = new Commande();
            $commande
                ->setFullName($faker->name())
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city());
            if ($faker->boolean(90)) {
                $commande->setStatus(Commande::STATUS_PAID);
            }
            $commande->setCustomer($faker->randomElement($users));
            $commande->setTotal(mt_rand(2000, 30000));

            $manager->persist($commande);
        }

        $manager->flush();
    }
}
