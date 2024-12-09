<?php

namespace App\Traits;

use App\Entity\Product;

trait OrderTrait
{
    public function from($order): array
    {
        return [
            'id' => $order->getId(),
            'status' => $order->getStatus(),
            'created_at' => $order->getCreatedAt(),
            'updated_at' => $order->getUpdatedAt(),
            'products' => array_map(function (Product $product) use ($order) {
                return [
                    'id' => $product->getProductId(),
                    'title' => $product->getProductTitle(),
                    'qty' => $order->getProductQuantities()[$product->getId()],
                ];
            }, $order->getProducts()->toArray()),
        ];
    }
}
