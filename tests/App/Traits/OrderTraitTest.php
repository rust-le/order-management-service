<?php

namespace App\Tests\Traits;

use App\Entity\Order;
use App\Entity\Product;
use App\Exception\InvalidRequestDataException;
use App\Repository\ProductRepository;
use App\Traits\OrderTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class OrderTraitTest extends TestCase
{
    use OrderTrait;

    public function testFrom()
    {
        $product = $this->createMock(Product::class);
        $product->method('getProductId')->willReturn(1);
        $product->method('getProductTitle')->willReturn('Product 1');
        $product->method('getId')->willReturn(1);

        $order = $this->createMock(Order::class);
        $order->method('getId')->willReturn(1);
        $order->method('getStatus')->willReturn('created');
        $order->method('getCreatedAt')->willReturn(new DateTimeImmutable());
        $order->method('getUpdatedAt')->willReturn(new DateTimeImmutable());
        $order->method('getProducts')->willReturn(new ArrayCollection([$product]));
        $order->method('getProductQuantities')->willReturn([1 => 2]);

        $data = $this->from($order);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertArrayHasKey('products', $data);
        $this->assertCount(1, $data['products']);
    }

    public function testInto()
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $product = $this->createMock(Product::class);

        $productRepository->method('findOneBy')->with(['productId' => 1])->willReturn($product);
        $product->method('getProductId')->willReturn(1);

        $data = [
            'products' => [
                ['id' => 1, 'qty' => 2],
            ],
        ];

        $order = $this->into($data, $productRepository);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('created', $order->getStatus());
        $this->assertCount(1, $order->getProducts());
    }

    public function testIntoThrowsExceptionWhenProductsAreMissing()
    {
        $this->expectException(InvalidRequestDataException::class);
        $this->expectExceptionMessage('Products are required');

        $productRepository = $this->createMock(ProductRepository::class);
        $data = [];

        $this->into($data, $productRepository);
    }

    public function testIntoThrowsExceptionWhenProductNotFound()
    {
        $this->expectException(InvalidRequestDataException::class);
        $this->expectExceptionMessage('Product not found for id 1');

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('findOneBy')->with(['productId' => 1])->willReturn(null);

        $data = [
            'products' => [
                ['id' => 1, 'qty' => 2],
            ],
        ];

        $this->into($data, $productRepository);
    }
}
