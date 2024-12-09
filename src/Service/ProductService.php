<?php

namespace App\Service;

use App\Entity\ProductRating;
use App\Repository\ProductRatingRepository;
use App\Repository\ProductRepository;
use App\Entity\Product;

class ProductService
{
    private ProductRepository $productRepository;
    private ProductRatingRepository $productRatingRepository;
    public function __construct(ProductRepository $productRepository, ProductRatingRepository $productRatingRepository)
    {
        $this->productRepository = $productRepository;
        $this->productRatingRepository = $productRatingRepository;
    }

    public function addAndUpdateProduct(array $products): void
    {
        array_walk($products, function ($product) {
            $productEntity = $this->productRepository->findOneBy(['productId' => $product['id']]);
            if(!$productEntity) {
                $productEntity = new Product();
            }
            $productEntity->setProductId($product['id']);
            $productEntity->setProductTitle($product['title']);
            $productEntity->setProductPrice($product['price']);
            $productEntity->setProductDescription($product['description']);
            $productEntity->setProductCategory($product['category']);
            $productEntity->setProductImage($product['image']);
            $this->productRepository->add($productEntity);
            $productRatingEntity = $this->productRatingRepository->findOneBy(['id' => $productEntity->getProductRating()]);
            if(!$productRatingEntity) {
                $productRatingEntity = new ProductRating();
            }
            $productRatingEntity->setProductRate($product['rating']['rate']);
            $productRatingEntity->setProductCount($product['rating']['count']);
            $productEntity->setProductRating($productRatingEntity);

            $this->productRepository->add($productEntity, true);
        });
    }
}
