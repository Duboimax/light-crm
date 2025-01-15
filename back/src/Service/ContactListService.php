<?php

namespace App\Service;

use Mailjet\Resources;
use App\Entity\Customer;

class ContactListService
{

    public function __construct(
        private readonly MailjetService $mailjetService
    )
    { }

    public function create(string $name): array
    {
        $client = $this->mailjetService->getClient();

        $response = $client->post(Resources::$Contactslist, [
            'body' => [
                'Name' => $name
            ]
        ]);

        if (!$response->success()) {
            throw new \Exception('Erreur lors de la création de la liste : ' . $response->getReasonPhrase());
        }

        return $response->getData();
    }

    public function addCustomerToContactList(int $listId, Customer $customer): array
    {
        $client = $this->mailjetService->getClient();

        $response = $client->post(Resources::$ContactslistManagemanycontacts, [
            'id' => $listId,
            'body' => [
                'Contacts' => [
                    [
                        'Email' => $customer->getEmail(),
                        'IsExcludedFromCampaigns' => 'false',
                        'Name' => $customer->getFirstname() . $customer->getLastname(),
                        'Properties' => "object"
                    ],
                ],
                'Action' => 'addnoforce',
            ],
        ]);

        if (!$response->success()) {
            throw new \Exception("Erreur lors de l'ajout du contact : " . $response->getReasonPhrase());
        }

        return $response->getData();
    }
}

