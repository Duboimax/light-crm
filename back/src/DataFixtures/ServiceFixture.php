<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Service;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ServiceFixture extends Fixture implements DependentFixtureInterface
{
    public const SERVICE_REFERENCE = 'service_';
    public function load(ObjectManager $manager): void
    {
        $adminUser = $this->getReference(UserFixture::ADMIN_USER_REFERENCE, User::class);

        for ($i = 0; $i <= 10; $i++) {
            $service = new Service();
            $service->setName("Service $i");
            $service->setDescription("Description for Service $i");
            $service->setHourlyRate(mt_rand(50, 150) / 10);
            $service->setDuration(mt_rand(30, 120));
            $service->setOwner($adminUser);

            $manager->persist($service);

            $this->addReference(self::SERVICE_REFERENCE . $i, $service);
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
