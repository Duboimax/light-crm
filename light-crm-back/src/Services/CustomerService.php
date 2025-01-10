<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CustomerRepository $customerRepository
    ) {}

    public function list(string $id): ?array
    {
        return $this->customerRepository->findByUserId($id);
    }

    public function getById(string $id): ?Customer
    {
        return $this->customerRepository->find($id);
    }

    public function create(Customer $customer, User $owner): Customer
    {
        $customer->setUser($owner);

        $this->em->persist($customer);
        $this->em->flush();

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $this->em->remove($customer);
        $this->em->flush();
    }


    public function update(Customer $customer): Customer
    {
        return $customer;
    }
}
