<?php

namespace App\Services;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private FormFactoryInterface $formFactory,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function getById(string $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function create(array $data): User
    {
        if (empty($data)) {
            throw new \InvalidArgumentException("Invalid data: Missing required fields.");
        }

        $form = $this->formFactory->create(UserCreateType::class, new User());
        $form->submit($data);

        if (!$form->isValid()) {
            $errors = (string) $form->getErrors(true, false);
            throw new \InvalidArgumentException("Invalid data: $errors");
        }

        /** @var User $user */
        $user = $form->getData();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function delete(string $id): bool
    {
        /** @var ?User $user */
        $user = $this->userRepository->find($id);

        if (!$user) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }

    public function update(string $id, array $data): User
    {
        /** @var ?User $user */
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \InvalidArgumentException("User not found for ID: $id");
        }

        $form = $this->formFactory->create(UserUpdateType::class, $user);
        $form->submit($data, false);

        if (!$form->isValid()) {
            throw new \InvalidArgumentException('Invalid data');
        }

        $this->entityManager->flush();

        return $user;
    }
}
