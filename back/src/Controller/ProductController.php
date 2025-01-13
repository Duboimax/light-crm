<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products_get_all', methods: ['GET'])]
    public function list(ProductRepository $productRepository): JsonResponse
    {
        $userId = $this->getUser()->getUserIdentifier();

        $products = $productRepository->findBy(['user' => $userId]);

        return $this->json($products, 200, [], ['groups' => 'products:read']);
    }

    #[Route('/products/{id}', name: 'products_get_by_id', methods: ['GET'])]
    public function getById(string $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'products:read']);
    }

    #[Route('/products', name: "products_create", methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(ProductType::class, new Product());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        /** @var Product $product */
        $product = $form->getData();
        $product->setUser($this->getUser());

        $em->persist($product);
        $em->flush();

        return $this->json($product, Response::HTTP_CREATED, [], ['groups' => 'products:read']);
    }

    #[Route('/products/{id}', name: 'products_update', methods: ['PATCH'])]
    public function update(Request $request, Product $product, EntityManagerInterface $em): JsonResponse
    {
        if ($product->getUser() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            return $this->json($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }

        $em->flush();

        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'products:read']);
    }

    #[Route('/products/{id}', name: "products_delete", methods: ['DELETE'])]
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        if ($product->getUser() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        $em->remove($product);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
