<?php

namespace App\Service\Http;

use App\Entity\User;
use App\Exception\EmptyBasketException;
use App\Exception\OrderNotFoundException;
use App\Exception\ShipmentMethodNotFoundException;
use App\Exception\StatusNotFoundException;
use App\Request\CreateOrderRequest;
use App\Request\PaginationRequest;
use App\Request\UpdateOrderRequest;
use App\Service\BasketService;
use App\Service\OrderService;
use App\Service\OrderShipmentMethodService;
use App\Service\OrderStatusService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly final class OrderHttpService
{
    public function __construct(
        protected OrderService $service,
        protected BasketService $basketService,
        protected OrderShipmentMethodService $orderShipmentMethodService,
        protected OrderStatusService $orderStatusService,
        protected SerializerInterface $serializer,
        protected EventDispatcherInterface $eventDispatcher
    ) {}

    public function list(User $user, PaginationRequest $request): JsonResponse
    {
        $list = $this->service->list($user, $request->getPage(), $request->getLimit());

        return new JsonResponse([
            'items' => $this->serializer->normalize($list, JsonEncoder::FORMAT, [
                'groups' => ['order'],
            ]),
            'total' => $this->service->count($user),
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
        ], JsonResponse::HTTP_OK);
    }

    public function item(User $user, int $id): JsonResponse
    {
        $item = $this->service->item($user, $id);

        if (!$item) {
            throw new OrderNotFoundException;
        }

        return new JsonResponse($this->serializer->normalize($item, JsonEncoder::FORMAT, [
            'groups' => ['order'],
        ]), JsonResponse::HTTP_OK);
    }

    public function create(User $user, CreateOrderRequest $request): JsonResponse
    {
        $basket = $this->basketService->list($user);

        if (empty($basket)) {
            throw new EmptyBasketException;
        }

        $shipmentMethod = $this->orderShipmentMethodService->item($request->getShipmentMethod());

        if (empty($shipmentMethod)) {
            throw new ShipmentMethodNotFoundException;
        }

        $order = $this->service->create($user, $request->getPhone(), $shipmentMethod);

        foreach ($basket as $basketItem) {
            $orderProduct = $this->service->addProduct($order, $basketItem);

            if ($orderProduct) {
                $this->basketService->delete($user, $basketItem);
            }
        }

        $this->service->execute();

        return new JsonResponse([
            'message' => "Order successfully created",
            'id' => $order->getId(),
        ], JsonResponse::HTTP_OK);
    }

    public function update(User $user, int $id, UpdateOrderRequest $request): JsonResponse
    {
        $order = $this->service->item($user, $id);

        if (!$order) {
            throw new OrderNotFoundException;
        }

        $status = $this->orderStatusService->item($request->getStatus());

        if (empty($status)) {
            throw new StatusNotFoundException;
        }

        $this->service->updateStatus($order, $status);
        $this->service->execute();

        return new JsonResponse([
            'message' => "Order successfully updated",
        ], JsonResponse::HTTP_OK);
    }
}
