<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerController extends AbstractController
{
    #[Route('/customers', name: 'customers_get_all', methods: ['GET'])]
    public function list(CustomerRepository $customerRepository): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();

        $customers = $customerRepository->findByUserId($userId);

        return $this->json($customers,  context: ['groups' => ['customer:read']]);
    }

    #[Route('customers/{id}', name: 'customers_get_by_id', methods: ['GET'])]
    public function getById(string $id, CustomerRepository $customerRepository): JsonResponse
    {
        $customer = $customerRepository->find($id);

        return $this->json($customer, Response::HTTP_OK);
    }

    #[Route('customers', name: "customers_create", methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(CustomerType::class, new Customer());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();
        $customer->setUser($this->getUser());

        $em->persist($customer);
        $em->flush();

        return $this->json($customer, Response::HTTP_CREATED);
    }

    #[Route('customers/{id}', name: 'customers_update', methods: ['PATCH'])]
    public function update(Request $request, Customer $customer, EntityManagerInterface $em)
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $data = $request->toArray();
        $form->submit($data);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json('invalid', Response::HTTP_BAD_REQUEST);
        }

        $em->flush();

        return $this->json($customer, context: ['groups' => 'customer:read']);
    }

    #[Route('customers/{id}', name: "customers_delete", methods: ['DELETE'])]
    public function delete(Customer $customer, EntityManagerInterface $em): JsonResponse
    {
        if ($customer->getUser() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        $em->remove($customer);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
