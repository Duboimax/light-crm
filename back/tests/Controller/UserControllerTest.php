<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends KernelTestCase
{
    private UserController $controller;

    protected function setUp(): void
    {
        $this->controller = (new UserController());
        $this->controller->setContainer($this->getContainer());
    }

    public function test_getAll(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('findAll')
            ->willReturn(['lots', 'of', 'users']);

        $response = $this->controller->getAll($userRepository);

        $this->assertEquals('["lots","of","users"]', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_getById(): void
    {
        $user = (new User())->setId('1');

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($user);

        $controller = $this->createPartialMock(UserController::class, ['json']);
        $controller->setContainer($this->getContainer());

        $controller->expects($this->once())
            ->method('json')
            ->with($user);

        $controller->getById('1', $userRepository);
    }

    public function test_getById_user_not_found(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $controller = $this->createPartialMock(UserController::class, ['json']);
        $controller->setContainer($this->getContainer());

        $controller->expects($this->once())
            ->method('json')
            ->with(
                [
                    'message' => 'User not found'
                ],
                Response::HTTP_NOT_FOUND
            );

        $controller->getById('1', $userRepository);
    }

    public function test_create(): void
    {
        $user = (new User())->setEmail('toto@toto.com');

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($this->callback(function (User $hashedUser) use ($user) {
                return $hashedUser->getEmail() === $user->getEmail();
            }), 'password')
            ->willReturn('hashed_password');

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (User $persistedUser) use ($user) {
                return $persistedUser->getEmail() === $user->getEmail()
                    && $persistedUser->getPassword() === 'hashed_password';
            }));

        $em->expects($this->once())
            ->method('flush');

        $response = $this->controller->create(new Request(content: json_encode([
            'email' => 'toto@toto.com',
            'password' => 'password'
        ])), $em, $passwordHasher);

        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('toto@toto.com', $responseData['email']);
    }

    public function test_update(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('flush');

        $user = new User();
        $user->setEmail('toto@test.com');

        $response = $this->controller->update(new Request(content: json_encode([
            'email' => 'toto@gmail.com'
        ])), $user, $em);


        $this->assertEquals(200, $response->getStatusCode());
        $response = json_decode($response->getContent(), true);
        $this->assertEquals([
            'id' => $user->getId(),
            'username' => 'toto@gmail.com',
            'email' => 'toto@gmail.com',
            'emailCampaigns' => [],
            'roles' => ['ROLE_USER'],
            'userIdentifier' => $user->getId(),
            'salt' => null,
            'createdAt' => $user->getCreatedAt()->format(\DateTime::ATOM)
        ], $response);
    }


    public function test_delete(): void
    {
        $user = (new User())->setId('1');

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('remove')
            ->with($user);

        $em->expects($this->once())
            ->method('flush');

        $response = $this->controller->delete($user, $em);
        $this->assertEquals(204, $response->getStatusCode());
    }
}
