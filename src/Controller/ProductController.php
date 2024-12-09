<?php

namespace App\Controller;

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
     * @Route("/products/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->findOneBy(['productId' => $id]);
        if (!$product) {
            return $this->json('No product found for id ' . $id, 404);
        }
        $data = $this->from($product);
        return $this->json($data, 200);
    }

    /**
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
