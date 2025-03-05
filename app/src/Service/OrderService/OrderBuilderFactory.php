<?php

namespace App\Service\OrderService;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Event\OrderItemOnAddedEvent;
use App\Event\OrderOnCreatedEvent;
use App\Event\OrderOnUpdatedEvent;
use App\Service\OrderService\AbstractFactory;
use App\Service\OrderService\Interface\OrderFactoryInterface;

readonly final class OrderBuilderFactory extends AbstractFactory implements OrderFactoryInterface
{
    public function list(User $user, $page = 1, $limit = 10): array
    {
        return $this->orderRepository->getOrderList($user, $page, $limit);
    }

    public function count(User $user): int
    {
        return $this->orderRepository->getOrdersCount($user);
    }

    public function get(User $user, int $id): ?Order
    {
        return $this->orderRepository->getOrderItem($user, $id);
    }

    public function getById(int $id): ?Order
    {
        return $this->orderRepository->getOrderItemById($id);
    }

    public function create(User $user, string $phone, OrderShipmentMethod $shipmentMethod): ?Order
    {
        $order = $this->orderRepository->createOrder($user, $phone, $shipmentMethod);

        if ($order) {
            $this->eventDispatcher->dispatch(new OrderOnCreatedEvent($order), OrderOnCreatedEvent::NAME);
        }

        return $order;
    }

    public function addProduct(Order $order, Basket $basketItem): OrderProduct
    {
        $orderItem = $this->orderRepository->addOrderItem($order, $basketItem);

        if ($orderItem) {
            $this->eventDispatcher->dispatch(new OrderItemOnAddedEvent($orderItem), OrderItemOnAddedEvent::NAME);
        }

        return $orderItem;
    }

    public function updateStatus(Order $order, OrderStatus $status): Order
    {
        $order = $this->orderRepository->updateOrderStatus($order, $status);

        if ($order) {
            $this->eventDispatcher->dispatch(new OrderOnUpdatedEvent($order), OrderOnUpdatedEvent::NAME);
        }

        return $order;
    }
}
