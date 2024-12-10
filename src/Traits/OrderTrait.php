<?php

namespace App\Traits;

use App\Entity\Product;
use App\Entity\Order;
use App\Exception\InvalidRequestDataException;
use DateTimeImmutable;

trait OrderTrait
{
    /**
     * Convert from Order entity to array
     *
     * @param Order $order
     * @return array
     */
    public function from(Order $order): array
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

    /**
     * Trying convert an array into Order entity
     *
     * @param array $data
     * @param $productRepository
     * @return Order
     */
    public function into(array $data, $productRepository): Order
    {
        if (!isset($data['products'])) {
            throw new InvalidRequestDataException('Products are required', 400);
        }
        $order = new Order();
        $order->setStatus('created');
        $order->setCreatedAt(new DateTimeImmutable());

        foreach ($data['products'] as $product) {
            $productEntity = $productRepository->findOneBy(['productId' => $product['id']]);
            if (!$productEntity) {
                throw new InvalidRequestDataException('Product not found for id ' . $product['id'], 400);
            }
            $order->addProduct($productEntity, $product['qty']);
        }
        return $order;
    }
}
