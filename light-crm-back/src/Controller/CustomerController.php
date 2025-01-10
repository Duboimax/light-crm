<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Services\CustomerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerController extends AbstractController
{

    public function __construct(
        private CustomerService $customerService,
        private FormFactoryInterface $formFactory
    ) {}

    #[Route('customers', name: 'customers_get_all', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();

        $customers = $this->customerService->list($userId);

        return $this->json($customers, 200, [], ['groups' => 'customer:read']);
    }

    #[Route('customers/{id}', name: 'customers_get_by_id', methods: ['GET'])]
    public function getById(string $id): JsonResponse
    {
        $customer = $this->customerService->getById($id);

        return $this->json($customer, Response::HTTP_OK);
    }

    #[Route('customers', name: "customers_create", methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $form = $this->createForm(CustomerType::class, new Customer());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();

        $newCustomer = $this->customerService->create($customer, $this->getUser());

        return $this->json($newCustomer, Response::HTTP_CREATED);
    }

    #[Route('customers/{id}', name: 'customers_update', methods: ['PATCH'])]
    public function update(Request $request, Customer $customer, EntityManagerInterface $em)
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $em->flush();

        return $this->json($customer);
    }

    #[Route('customers/{id}', name: "customers_delete", methods: ['DELETE'])]
    public function delete(Customer $customer): JsonResponse
    {
        if ($customer->getUser() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        $this->customerService->delete($customer);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
