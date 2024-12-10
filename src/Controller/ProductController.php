<?php

namespace App\Controller;

use App\Exception\InvalidRequestDataException;
use App\Repository\ProductRepository;
use App\Service\FakeStoreApiService;
use App\Service\ProductService;
use App\Traits\ProductTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    use ProductTrait;

    /**
     * Sync products from FakeStore API and list all products
     *
     * @Route("/products/sync", name="app_products_sync", methods={"POST"})
     */
    public function sync(
        FakeStoreApiService $fakeApiService,
        ProductService      $productService
    ): JsonResponse
    {
        $data = $fakeApiService->getProducts();
        $productService->addAndUpdateProduct($data);
        return $this->json($data, 200);
    }

    /**
     * Show a single product
     *
     * @Route("/products/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->findOneBy(['productId' => $id]);
        if (!$product) {
            throw new InvalidRequestDataException('No product found for id ' . $id, 404);
        }
        $data = $this->from($product);
        return $this->json($data, 200);
    }

    /**
     * List all products
     *
     * @Route("/products", name="app_products", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = $this->from($product);
        }
        return $this->json($data, 200);
    }
}
