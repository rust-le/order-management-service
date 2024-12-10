<?php

namespace App\Traits;

use App\Entity\Product;

trait ProductTrait
{
    /**
     * Convert from Product entity to array
     *
     * @param Product $product
     * @return array
     */
    public function from(Product $product): array
    {
        $productRating = $product->getProductRating();
        return [
            'id' => $product->getProductId(),
            'title' => $product->getProductTitle(),
            'price' => $product->getProductPrice(),
            'description' => $product->getProductDescription(),
            'category' => $product->getProductCategory(),
            'image' => $product->getProductImage(),
            'rating' => $productRating ? [
                'rate' => $productRating->getProductRate(),
                'count' => $productRating->getProductCount(),
            ] : null,
        ];
    }
}
