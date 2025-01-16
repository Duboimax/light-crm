<?php

// src/Controller/AuthController.php
namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthController extends AbstractController
{

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

    #[Route('/refresh', name: 'refresh_token', methods: ['POST'])]
    public function refresh(
        JWTTokenManagerInterface $jwtManager,
        Request $request
    ): JsonResponse {
        $user = $this->getUser();
        
        if (!$user instanceof User) {
            return $this->json(
                ['error' => 'Token invalide ou utilisateur non authentifié.'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $newToken = $jwtManager->create($user);

        return $this->json([
            'token' => $newToken,
            'expiresIn' => 3600, 
        ], Response::HTTP_OK);
    }
}
