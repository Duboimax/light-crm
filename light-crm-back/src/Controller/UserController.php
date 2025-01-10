<?php

// src/Controller/UserController.php
namespace App\Controller;

use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}

    #[Route('users', name: 'user_get_all', methods: ["GET"])]
    public function getAll(): JsonResponse
    {
        $users = $this->userService->getAll();

        return $this->json($users, 200, ['groups' => ['user_read']]);
    }

    #[Route('users/{id}', name: 'user_get_by_id', methods: ["GET"])]
    public function getById(string $id): JsonResponse
    {
        $user = $this->userService->getById($id);

        if (!$user) {
            return  $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user);
    }

    #[Route('users', name: 'user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->create($data);

            return $this->json($user, Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('users/{id}', name: 'user_update', methods: ['PATCH'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->userService->update($id, $data);

            return $this->json($user);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('users/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse
    {
        $success = $this->userService->delete($id);

        if (!$success) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['message' => 'User deleted successfully']);
    }
}
