<?php

namespace App\Traits;

use App\Entity\Product;
use App\Entity\ProductRating;
use PHPUnit\Framework\TestCase;

class ProductTraitTest extends TestCase
{
    use ProductTrait;

    public function testFrom()
    {
        $product = $this->createMock(Product::class);
        $productRating = $this->createMock(ProductRating::class);

        $product->method('getProductId')->willReturn(1);
        $product->method('getProductTitle')->willReturn('Test Product');
        $product->method('getProductPrice')->willReturn(99.99);
        $product->method('getProductDescription')->willReturn('Test Description');
        $product->method('getProductCategory')->willReturn('Test Category');
        $product->method('getProductImage')->willReturn('test.jpg');
        $product->method('getProductRating')->willReturn($productRating);

        $productRating->method('getProductRate')->willReturn(4.5);
        $productRating->method('getProductCount')->willReturn(100);

        $expected = [
            'id' => 1,
            'title' => 'Test Product',
            'price' => 99.99,
            'description' => 'Test Description',
            'category' => 'Test Category',
            'image' => 'test.jpg',
            'rating' => [
                'rate' => 4.5,
                'count' => 100,
            ],
        ];

        $this->assertEquals($expected, $this->from($product));
    }

    public function testFromWithoutRating()
    {
        $product = $this->createMock(Product::class);

        $product->method('getProductId')->willReturn(1);
        $product->method('getProductTitle')->willReturn('Test Product');
        $product->method('getProductPrice')->willReturn(99.99);
        $product->method('getProductDescription')->willReturn('Test Description');
        $product->method('getProductCategory')->willReturn('Test Category');
        $product->method('getProductImage')->willReturn('test.jpg');
        $product->method('getProductRating')->willReturn(null);

        $expected = [
            'id' => 1,
            'title' => 'Test Product',
            'price' => 99.99,
            'description' => 'Test Description',
            'category' => 'Test Category',
            'image' => 'test.jpg',
            'rating' => null,
        ];

        $this->assertEquals($expected, $this->from($product));
    }
}
