<?php

namespace App\Tests\User\Unit;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase
{
    private EntityManagerInterface&MockObject $em;

    private UserRepository&MockObject $userRepository;
    private UserPasswordHasherInterface&MockObject $passwordHasher;
    private FormFactoryInterface&MockObject $formFactory;

    private UserService $userService;

    protected function setUp(): void
    {
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);

        $this->userRepository = $this->createMock(UserRepository::class);

        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->userService = new UserService(
            $this->em,
            $this->userRepository,
            $this->formFactory,
            $this->passwordHasher
        );
    }

    public function test_getAll(): void
    {
        $users = [
            (new User)->setEmail('toto@gmail.com'),
            (new User)->setEmail('toto2@gmail.com'),
        ];
        $this->userRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($users);

        $this->assertEquals($users, $this->userService->getAll());
    }

    public function test_getById(): void
    {
        $user = (new User)->setEmail('toto@gmail.com');
        $id = $user->getId();

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($user);

        $this->assertEquals($user, $this->userService->getById($id));
    }

    public function test_create()
    {
        $data = ['email' => 'johndoe@gmail.com', 'password' => 'securepassword'];

        $form = $this->createMock(\Symfony\Component\Form\Form::class);
        $form->expects($this->once())
            ->method('submit')
            ->with($data);
        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $user = new User();
        $form->expects($this->once())
            ->method('getData')
            ->willReturn($user);

        $this->formFactory->expects($this->once())
            ->method('create')
            ->with(UserCreateType::class, $this->isInstanceOf(User::class))
            ->willReturn($form);

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'securepassword')
            ->willReturn('hashedPassword');

        $this->em->expects($this->once())
            ->method('persist')
            ->with($user);
        $this->em->expects($this->once())
            ->method('flush');

        $result = $this->userService->create($data);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame('hashedPassword', $result->getPassword());
    }

    // public function test_update(): void
    // {
    //     $data = ['email' => 'max@gmail.com'];

    //     $user = (new User())
    //         ->setId('123')
    //         ->setEmail('bidule@gmail.com');

    //     $this->userRepository->expects($this->once())
    //         ->method('find')
    //         ->with('123')
    //         ->willReturn($user);

    //     $form = $this->createMock(\Symfony\Component\Form\Form::class);
    //     $form->expects($this->once())
    //         ->method('submit')
    //         ->with($data);

    //     $form->expects($this->once())
    //         ->method('isValid')
    //         ->willReturn(true);

    //     $this->formFactory->expects($this->once())
    //         ->method('create')
    //         ->with(UserUpdateType::class, $user)
    //         ->willReturn($form);

    //     $this->em->expects($this->once())
    //         ->method('flush');

    //     $result = $this->userService->update('123', $data);

    //     $this->assertEquals($data['email'], $result->getEmail());
    // }

    public function test_delete(): void
    {
        $user = (new User)->setEmail('toto@gmail.com');
        $id = $user->getId();

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($user);

        $this->em->expects($this->once())->method('remove')->with($user);
        $this->em->expects($this->once())->method('flush');

        $this->assertTrue($this->userService->delete($id));
    }
}
