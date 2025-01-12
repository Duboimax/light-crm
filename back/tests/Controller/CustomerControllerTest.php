<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Controller\CustomerController;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerControllerTest extends KernelTestCase
{
    private CustomerController $controller;

    protected function setUp(): void
    {
        $this->controller = (new CustomerController());
        $this->controller->setContainer($this->getContainer());
    }

    public function test_list(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getUserIdentifier')
            ->willReturn('userId');

        $customers = [
            (new Customer())->setEmail('toto@gmail.com'),
            (new Customer())->setEmail('tito@gmail.com') 
        ];

        $customerRepository = $this->createMock(CustomerRepository::class);
        $customerRepository->expects($this->once())
            ->method('findByUserId')
            ->with('userId')
            ->willReturn($customers);

        $controller = $this->createPartialMock(CustomerController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        
        $response = $controller->list($customerRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(2, $responseData);
    }

    public function test_getById(): void
    {
        $customer = (new Customer())->setEmail('customer@example.com');

        $customerRepository = $this->createMock(CustomerRepository::class);
        $customerRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($customer);

        $response = $this->controller->getById('1', $customerRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('customer@example.com', $responseData['email']);
    }

    public function test_create(): void
    {
        $request = new Request(content: json_encode([
            'email' => 'new.customer@example.com',
            'name' => 'New Customer',
        ]));

        $user = new User();
        $user->setEmail('toto@gmail.com');

        $controller = $this->createPartialMock(CustomerController::class, ['getUser']);
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
        $this->assertEquals('new.customer@example.com', $responseData['email']);
    }
    

    public function test_update(): void
    {
        $customer = (new Customer())
            ->setName('old Cutomer')
            ->setEmail('old.customer@example.com');

        $request = new Request(content: json_encode([
            'name' => 'old Customer',
            'email' => 'updated.customer@example.com',
        ]));

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $response = $this->controller->update($request, $customer, $em);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('updated.customer@example.com', $responseData['email']);
    }

    public function test_delete(): void
    {
        $customer = $this->createMock(Customer::class);
        $user = $this->createMock(User::class);

        $controller = $this->createPartialMock(CustomerController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $customer->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($customer);
        $em->expects($this->once())->method('flush');

        $response = $controller->delete($customer, $em);

        $this->assertEquals(204, $response->getStatusCode());
    }
}

