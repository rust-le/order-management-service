<?php

namespace App\Traits;

trait ProductTrait
{
    public function from($product): array
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
