<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Service;
use App\Controller\ServiceController;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class ServiceControllerTest extends KernelTestCase
{
    private ServiceController $controller;

    protected function setUp(): void
    {
        $this->controller = (new ServiceController());
        $this->controller->setContainer($this->getContainer());
    }

    public function test_list(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getUserIdentifier')
            ->willReturn('userId');

        $services = [
            (new Service())->setName('Service 1')->setHourlyRate(50),
            (new Service())->setName('Service 2')->setHourlyRate(75),
        ];

        $serviceRepository = $this->createMock(ServiceRepository::class);
        $serviceRepository->expects($this->once())
            ->method('findBy')
            ->with(['owner' => 'userId'])
            ->willReturn($services);

        $controller = $this->createPartialMock(ServiceController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        
        $response = $controller->list($serviceRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(2, $responseData);
    }

    public function test_getById(): void
    {
        $service = (new Service())->setName('Service 1')->setHourlyRate(50);

        $serviceRepository = $this->createMock(ServiceRepository::class);
        $serviceRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($service);

        $response = $this->controller->getById('1', $serviceRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Service 1', $responseData['name']);
    }

    public function test_create(): void
    {
        $request = new Request(content: json_encode([
            'name' => 'New Service',
            'description' => 'Service description',
            'hourlyRate' => 100,
            'duration' => 60,
        ]));

        $user = new User();
        $user->setEmail('owner@example.com');

        $controller = $this->createPartialMock(ServiceController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $response = $controller->create($request, $em);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('New Service', $responseData['name']);
    }

    public function test_update(): void
    {
        $service = (new Service())
            ->setName('Old Service')
            ->setDescription('Old description')
            ->setHourlyRate(50)
            ->setDuration(30);

        $request = new Request(content: json_encode([
            'name' => 'Updated Service',
            'description' => 'Updated description',
            'hourlyRate' => 75,
            'duration' => 45,
        ]));

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $response = $this->controller->update($request, $service, $em);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Updated Service', $responseData['name']);
    }

    public function test_delete(): void
    {
        $service = $this->createMock(Service::class);
        $user = $this->createMock(User::class);

        $controller = $this->createPartialMock(ServiceController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $service->expects($this->once())
            ->method('getOwner')
            ->willReturn($user);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($service);
        $em->expects($this->once())->method('flush');

        $response = $controller->delete($service, $em);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
