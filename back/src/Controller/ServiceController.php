<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'services_get_all', methods: ['GET'])]
    public function list(ServiceRepository $serviceRepository): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();

        $services = $serviceRepository->findBy(['owner' => $userId]);

        return $this->json($services, context: ['groups' => ['service:read']]);
    }

    #[Route('/services/{id}', name: 'services_get_by_id', methods: ['GET'])]
    public function getById(string $id, ServiceRepository $serviceRepository): JsonResponse
    {
        $service = $serviceRepository->find($id);

        return $this->json($service, Response::HTTP_OK, context: ['groups' => 'service:read']);
    }

    #[Route('/services', name: 'services_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(ServiceType::class, new Service());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Service $service */
        $service = $form->getData();
        $service->setOwner($this->getUser());

        $em->persist($service);
        $em->flush();

        return $this->json($service, Response::HTTP_CREATED, context: ['groups' => ['service:read']]);
    }

    #[Route('/services/{id}', name: 'services_update', methods: ['PATCH'])]
    public function update(Request $request, Service $service, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(ServiceType::class, $service);
        $data = $request->toArray();
        $form->submit($data);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json('invalid', Response::HTTP_BAD_REQUEST);
        }

        $em->flush();

        return $this->json($service, context: ['groups' => 'service:read']);
    }

    #[Route('/services/{id}', name: 'services_delete', methods: ['DELETE'])]
    public function delete(Service $service, EntityManagerInterface $em): JsonResponse
    {
        if ($service->getOwner() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        $em->remove($service);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
