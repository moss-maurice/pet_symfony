<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\EmptyBasketException;
use App\Exception\OrderNotFoundException;
use App\Exception\ShipmentMethodNotFoundException;
use App\Exception\StatusNotFoundException;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderShipmentMethodRepository;
use App\Repository\OrderStatusRepository;
use App\Request\CreateOrderRequest;
use App\Request\PaginationRequest;
use App\Request\UpdateOrderRequest;
use App\Service\BasketService;
use App\Service\OrderService\OrderBuilderFactory;
use App\Service\OrderService\OrderCatalogBuilderFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class OrderService
{
    protected OrderBuilderFactory $factory;
    protected OrderCatalogBuilderFactory $catalogFactory;

    public function __construct(
        readonly protected OrderRepository $orderRepository,
        readonly protected OrderStatusRepository $orderStatusRepository,
        readonly protected OrderProductRepository $orderProductRepository,
        readonly protected OrderShipmentMethodRepository $orderShipmentMethodRepository,
        readonly protected BasketService $basketService,
        readonly protected SerializerInterface $serializer,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = new OrderBuilderFactory($this->orderRepository, $this->orderStatusRepository, $this->orderProductRepository, $this->eventDispatcher);
        $this->catalogFactory = new OrderCatalogBuilderFactory($this->orderShipmentMethodRepository, $this->orderStatusRepository);
    }

    public function factory()
    {
        return $this->factory;
    }

    public function catalogFactory()
    {
        return $this->catalogFactory;
    }

    public function getOrderList(User $user, PaginationRequest $request): JsonResponse
    {
        $orders = $this->factory->list($user, $request->getPage(), $request->getLimit());

        return new JsonResponse([
            'items' => $this->serializer->normalize($orders, JsonEncoder::FORMAT, [
                'groups' => ['order'],
            ]),
            'total' => $this->factory->count($user),
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
        ], JsonResponse::HTTP_OK);
    }

    public function createOrder(User $user, CreateOrderRequest $request): JsonResponse
    {
        $basket = $this->basketService->factory()->list($user);

        if (empty($basket)) {
            throw new EmptyBasketException;
        }

        $shipmentMethod = $this->orderShipmentMethodRepository->item($request->getShipmentMethod());

        if (empty($shipmentMethod)) {
            throw new ShipmentMethodNotFoundException;
        }

        $order = $this->factory->create($user, $request->getPhone(), $shipmentMethod);

        foreach ($basket as $basketItem) {
            $orderProduct = $this->factory->addProduct($order, $basketItem);

            if ($orderProduct) {
                $this->basketService->factory()->delete($user, $basketItem);
            }
        }

        return new JsonResponse([
            'message' => "Order successfully created",
            'id' => $order->getId(),
        ], JsonResponse::HTTP_OK);
    }

    public function getOrder(User $user, int $id): JsonResponse
    {
        $order = $this->factory->get($user, $id);

        if (!$order) {
            throw new OrderNotFoundException;
        }

        return new JsonResponse($this->serializer->normalize($order, JsonEncoder::FORMAT, [
            'groups' => ['order'],
        ]), JsonResponse::HTTP_OK);
    }

    public function updateOrder(User $user, int $id, UpdateOrderRequest $request): JsonResponse
    {
        $order = $this->factory->get($user, $id);

        if (!$order) {
            throw new OrderNotFoundException;
        }

        $status = $this->orderStatusRepository->item($request->getStatus());

        if (empty($status)) {
            throw new StatusNotFoundException;
        }

        $this->factory->updateStatus($order, $status);

        return new JsonResponse([
            'message' => "Order successfully updated",
        ], JsonResponse::HTTP_OK);
    }

    public function getStatusesList(): JsonResponse
    {
        $list = $this->catalogFactory->statusesList();

        return new JsonResponse([
            'items' => $this->serializer->normalize($list, JsonEncoder::FORMAT, [
                'groups' => ['catalog'],
            ]),
        ], JsonResponse::HTTP_OK);
    }

    public function getShipmentMethodsList(): JsonResponse
    {
        $list = $this->catalogFactory->shipmentsMethodsList();

        return new JsonResponse([
            'items' => $this->serializer->normalize($list, JsonEncoder::FORMAT, [
                'groups' => ['catalog'],
            ]),
        ], JsonResponse::HTTP_OK);
    }
}
