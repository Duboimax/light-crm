<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CustomerFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        /** @var User $adminUser */
        $adminUser = $this->getReference(UserFixture::ADMIN_USER_REFERENCE, User::class);

        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setUser($adminUser);
            $customer->setName($faker->name());
            $customer->setEmail($faker->unique()->email());
            $customer->setPhone('0110101010');
            $customer->setAddress($faker->address());

            $manager->persist($customer);
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
