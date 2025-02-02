<?php

// src/Controller/UserController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    #[Route('/users/list', name: 'user_get_all', methods: ["GET"])]
    public function getAll(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json($users, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'user_get_by_id', methods: ["GET"])]
    public function getById(string $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return  $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, 200, [], ['groups' => 'user:read']);
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $form = $this->createForm(UserCreateType::class, new User());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = (string) $form->getErrors(true, false);
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $form->getData();
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $user->setUsername($user->getEmail());

        $em->persist($user);
        $em->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'user_update', methods: ['PATCH'])]
    public function update(Request $request, User $user, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $user->setUsername($user->getEmail());

        $em->flush();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/users/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/users', name: 'user_me', methods: ['GET'])]
    public function current(UserRepository $userRepository): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();
        $user = $userRepository->find($userId);
        
        if (!$user) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($user, Response::HTTP_OK, context: ['groups' => 'user:read']);
    }
}
