<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Enum\SaleStatus;
use App\Repository\SaleRepository;
use App\Repository\CustomerRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
        CustomerRepository $customerRepository,
        ServiceRepository $serviceRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): Response {
        $data = json_decode($request->getContent(), true);

        $customer = $customerRepository->find($data['customer']);
        $service = $serviceRepository->find($data['service']);

        if (!$customer || !$service) {
            return $this->json(['message' => 'Client ou service non trouvé.'], Response::HTTP_BAD_REQUEST);
        }

        $sale = new Sale();
        $sale->setUser($this->getUser());
        $sale->setCustomer($customer);
        $sale->setService($service);
        $sale->setSaleDate(new \DateTime($data['saleDate'] ?? 'now'));
        $sale->setTotal($data['total']);
        $sale->setDiscount($data['discount'] ?? null);
        $sale->setComment($data['comment'] ?? null);
        $sale->setStatus($data['status'] ?? 'pending');
        $sale->setPaymentMethod($data['paymentMethod'] ?? null);

        $errors = $validator->validate($sale);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($sale);
        $entityManager->flush();

        $json = $serializer->serialize($sale, 'json', ['groups' => 'sale:read']);
        return new Response($json, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'sales_show', methods: ['GET'])]
    public function show(Sale $sale, SerializerInterface $serializer): Response
    {
        $this->denyAccessUnlessGranted('view', $sale);

        $json = $serializer->serialize($sale, 'json', ['groups' => 'sale:read']);
        return new Response($json, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'sales_cancel', methods: ['PATCH'])]
    public function cancel(Sale $sale, EntityManagerInterface $entityManager): Response
    {
//        $this->denyAccessUnlessGranted('update', $sale);
        if ($sale->getUser() !== $this->getUser()) {
            throw new AccessDeniedException();
        }
        $sale->setStatus(SaleStatus::CANCELLED);
        $entityManager->flush();

        return $this->json(['message' => 'Vente annulée avec succès.'], Response::HTTP_OK);
    }
}
