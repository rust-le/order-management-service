<?php

namespace App\Controller;

use App\Exception\InvalidRequestDataException;
use App\Helper\RequestHelper;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Traits\OrderTrait;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    use OrderTrait;

    private const STATUS = ['pending', 'deleted'];
    private RequestHelper $requestHelper;

    public function __construct(RequestHelper $requestHelper)
    {
        $this->requestHelper = $requestHelper;
    }

    /**
     * List all orders
     *
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
     * Returns a single order
     *
     * @Route("/orders/{id}", name="app_order_show", methods={"GET"})
     */
    public function show(int $id, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            throw new InvalidRequestDataException('No order found for id ' . $id, 404);
        }
        $data = $this->from($order);
        return $this->json($data, 200);
    }

    /**
     * Update an order status
     *
     * @Route("/orders/{id}", name="app_order_update", methods={"PATCH"})
     */
    public function update(
        int             $id,
        OrderRepository $orderRepository,
        Request         $request
    ): JsonResponse
    {
        $data = $this->requestHelper->checkRequestData($request);
        $order = $orderRepository->find($id);
        if (!$order) {
            throw new InvalidRequestDataException('No order found for id ' . $id, 404);
        }
        if (!isset($data['status']) || !in_array($data['status'], self::STATUS)) {
            throw new InvalidRequestDataException('Invalid or missing status code', 400);
        }
        if ($data['status'] === $order->getStatus()) {
            return $this->json('', 204);
        }
        $order->setStatus($data['status']);
        $order->setUpdatedAt(new DateTimeImmutable());
        $orderRepository->add($order, true);
        return $this->json($this->from($order), 200);
    }

    /**
     * Create a new order
     *
     * @Route("/orders", name="app_order_create", methods={"POST"})
     */
    public function create(
        OrderRepository   $orderRepository,
        ProductRepository $productRepository,
        Request           $request
    ): JsonResponse
    {
        $data = $this->requestHelper->checkRequestData($request);
        $order = $this->into($data, $productRepository);
        $orderRepository->add($order, true);
        return $this->json('Order created successfully, id: ' . $order->getId(), 201);
    }
}
