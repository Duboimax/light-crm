<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ServiceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $adminUser = $this->getReference(UserFixture::ADMIN_USER_REFERENCE, User::class);

        for ($i = 1; $i <= 5; $i++) {
            $service = new Service();
            $service->setName("Service $i");
            $service->setDescription("Description for Service $i");
            $service->setHourlyRate(mt_rand(50, 150) / 10);
            $service->setDuration(mt_rand(30, 120));
            $service->setOwner($adminUser);

            $manager->persist($service);
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
