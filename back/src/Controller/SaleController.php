<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Enum\SaleStatus;
use App\Form\SaleType;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sales')]
class SaleController extends AbstractController
{
    #[Route('', name: 'sales_index', methods: ['GET'])]
    public function index(SaleRepository $saleRepository, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $sales = $saleRepository->findBy(['user' => $user]);
        $json = $serializer->serialize($sales, 'json', ['groups' => 'sale:read']);
        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('', name: 'sales_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $form = $this->createForm(SaleType::class, new Sale());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json([
                'message' => 'Invalid data',
                'errors' => (string) $form->getErrors(true, false),
            ], Response::HTTP_BAD_REQUEST);
        }
        /** @var Sale $sale */
        $sale = $form->getData();
        $sale->setUser($this->getUser());

        $entityManager->persist($sale);
        $entityManager->flush();

        return $this->json($sale, Response::HTTP_CREATED, context: ['groups' => ['service:read']]);
    }

    #[Route('/{id}', name: 'sales_show', methods: ['GET'])]
    public function show(Sale $sale, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('view', $sale);

        $json = $serializer->serialize($sale, 'json', ['groups' => 'sale:read']);
        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'sales_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Sale $sale,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse {
        // Vérifier que l'utilisateur connecté est le propriétaire de la vente
        if ($sale->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier cette vente.');
        }

        // Créer et soumettre le formulaire avec les données de la requête
        $form = $this->createForm(SaleType::class, $sale);
        $form->submit(json_decode($request->getContent(), true), false); // false pour une mise à jour partielle

        if (!$form->isValid()) {
            return $this->json([
                'message' => 'Données invalides',
                'errors' => (string) $form->getErrors(true, false),
            ], Response::HTTP_BAD_REQUEST);
        }

        // Persister et sauvegarder les modifications
        $entityManager->flush();

        return $this->json($sale, Response::HTTP_OK, context: ['groups' => ['service:read']]);
    }

}
