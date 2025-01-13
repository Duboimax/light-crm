<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Controller\ProductController;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductControllerTest extends KernelTestCase
{
    private ProductController $controller;

    protected function setUp(): void
    {
        $this->controller = (new ProductController());
        $this->controller->setContainer($this->getContainer());
    }

    public function test_list(): void
    {
        $user = $this->createMock(UserInterface::class);
        $user->expects($this->once())
            ->method('getUserIdentifier')
            ->willReturn('userId');

        $products = [
            (new Product())->setName('Product 1'),
            (new Product())->setName('Product 2')
        ];

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => 'userId'])
            ->willReturn($products);

        $controller = $this->createPartialMock(ProductController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $response = $controller->list($productRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(2, $responseData);
    }

    public function test_getById(): void
    {
        $product = (new Product())->setName('Product 1');

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($product);

        $response = $this->controller->getById('1', $productRepository);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product 1', $responseData['name']);
    }

    public function test_create(): void
    {
        $request = new Request(content: json_encode([
            'name' => 'New Product',
            'price' => 100
        ]));

        $user = new User();
        $user->setEmail('user@example.com');

        $controller = $this->createPartialMock(ProductController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $form = $this->createMock(ProductType::class);

        $response = $controller->create($request, $em);

        $this->assertEquals(201, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('New Product', $responseData['name']);
    }

    public function test_update(): void
{
    $user = $this->createMock(User::class);
    $user->method('getUserIdentifier')->willReturn('userId');

    $product = (new Product())
        ->setName('Old Product')
        ->setUser($user);

    $request = new Request(content: json_encode([
        'name' => 'Updated Product',
        'price' => 150
    ]));

    $controller = $this->createPartialMock(ProductController::class, ['getUser']);
    $controller->setContainer($this->getContainer());
    $controller->expects($this->once())
        ->method('getUser')
        ->willReturn($user);

    $em = $this->createMock(EntityManagerInterface::class);
    $em->expects($this->once())->method('flush');

    $response = $controller->update($request, $product, $em);

    $this->assertEquals(200, $response->getStatusCode());
    $responseData = json_decode($response->getContent(), true);
    $this->assertEquals('Updated Product', $responseData['name']);
}


    public function test_delete(): void
    {
        $product = $this->createMock(Product::class);
        $user = $this->createMock(User::class);

        $controller = $this->createPartialMock(ProductController::class, ['getUser']);
        $controller->setContainer($this->getContainer());
        $controller->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $product->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($product);
        $em->expects($this->once())->method('flush');

        $response = $controller->delete($product, $em);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
