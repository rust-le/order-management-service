<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Traits\OrderTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;

class OrderController extends AbstractController
{
    use OrderTrait;
    /**
     * @Route("/orders", name="app_orders", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): JsonResponse
    {
        $orders = $orderRepository->findAll();
        $data = [];
        foreach ($orders as $order) {
            $data[] = $this->from($order);
        }
        return $this->json($data, 200);
    }

    /**
     * @Route("/orders/{id}", name="app_order_show", methods={"GET"})
     */
    public function show(int $id, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            return $this->json('No order found for id ' . $id, 404);
        }
        $data = $this->from($order);
        return $this->json($data, 200);
    }

    /**
     * @Route("/orders/{id}", name="app_order_update", methods={"PATCH"})
     */
    public function update(
        int $id,
        OrderRepository $orderRepository
    ): JsonResponse
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            return $this->json('No order found for id ' . $id, 404);
        }
        $order->setStatus('shipped'); // TODO get this valiue from request
        $order->setUpdatedAt(new \DateTimeImmutable());
        $orderRepository->add($order, true);
        return $this->json($this->from($order), 200); // TODO return order data and proper ressponse status
    }

    /**
     * @Route("/orders", name="app_order_create", methods={"POST"})
     */
    public function create(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ): JsonResponse
    {
        $order = new Order();
        $order->setStatus('created');
        $order->setCreatedAt(new \DateTimeImmutable());
        $product1 = $productRepository->findOneBy(['productId' => 3]);
        if($product1) {
            $order->addProduct($product1,333);
        }
        $product2 = $productRepository->findOneBy(['productId' => 4]);
        if($product2) {
            $order->addProduct($product2, 331);
        }
        $orderRepository->add($order, true);
        return $this->json('Order created successfully', 201);
    }
}
