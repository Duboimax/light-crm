<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\ContactListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactListController extends AbstractController{
  

    #[Route('/contact-list', name: 'contact-list_add_user', methods: ['POST'])]
    public function create(Request $request, ContactListService $contactListService): Response
    {
        $data = $request->toArray();
        
        if (!isset($data['name'])) {
            return $this->json(['error' => 'Le nom de la liste est requis.'], Response::HTTP_BAD_REQUEST);
        }

        $response = $contactListService->create($data['name']);

        return $this->json($response, Response::HTTP_OK);
    }


    #[Route('/contact-list/customer/{id}')]
    public function addUser(Request $request, Customer $customer, ContactListService $contactListService)
    {
        $data = $request->toArray();

        $contactList = $contactListService->addCustomerToContactList($data['contactList_id'], $customer);

        return $this->json($contactList, Response::HTTP_OK);
    }
}
