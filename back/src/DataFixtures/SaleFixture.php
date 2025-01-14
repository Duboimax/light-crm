<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Sale;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SaleFixture extends Fixture implements DependentFixtureInterface
{
    public const SALE_REFERENCE = 'sale_fixture_';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Récupérer l'utilisateur administrateur via la référence
        /** @var User $adminUser */
        $adminUser = $this->getReference(UserFixture::ADMIN_USER_REFERENCE, User::class);

        $customers = [];
        $services = [];

        for ($i = 0; $i < 10; $i++) {
            $customers[] = $this->getReference(CustomerFixture::CUSTOMER_REFERENCE . $i, Customer::class);
        }

        for ($i = 0; $i < 10; $i++) {
            $services[] = $this->getReference(ServiceFixture::SERVICE_REFERENCE . $i, Service::class);
        }

        for ($i = 0; $i < 10; $i++) {
            $sale = new Sale();
            $sale->setUser($adminUser);
            $sale->setCustomer($faker->randomElement($customers));
            $sale->setService($faker->randomElement($services));
            $sale->setSaleDate($faker->dateTimeThisYear);
            $sale->setTotal($faker->randomFloat(2, 50, 500));
            $sale->setDiscount($faker->randomElement([null, $faker->randomFloat(2, 5, 50)]));
            $sale->setComment($faker->optional()->sentence());
            $sale->setStatus($faker->randomElement(['completed', 'pending', 'cancelled']));
            $sale->setPaymentMethod($faker->randomElement(['credit_card', 'cash', 'bank_transfer']));

            $manager->persist($sale);

            $this->addReference(self::SALE_REFERENCE . $i, $sale);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CustomerFixture::class,
            ServiceFixture::class,
        ];
    }
}
