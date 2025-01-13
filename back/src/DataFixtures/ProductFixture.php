<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixture extends Fixture
{
    public const PRODUCT_REFERENCE = 'product-example';

    public function load(ObjectManager $manager): void
    {
        // Utilisation de Faker pour générer des données factices
        $faker = Factory::create();

        // On récupère l'utilisateur admin pour l'attribuer au produit
        $adminUser = $this->getReference(UserFixture::ADMIN_USER_REFERENCE, User::class);

        // Création de plusieurs produits
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setId(Uuid::v7()->toRfc4122());
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setPrice($faker->randomFloat(2, 10, 100));  // Prix entre 10 et 100
            $product->setStockQuantity($faker->numberBetween(1, 100)); // Quantité en stock entre 1 et 100
            $product->setUser($adminUser);  // Assigner l'utilisateur admin à chaque produit
            $product->setCreatedAt($faker->dateTimeThisYear());  // Date de création aléatoire dans l'année en cours

            $manager->persist($product);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
