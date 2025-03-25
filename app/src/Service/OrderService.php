<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Event\OrderItemOnAddedEvent;
use App\Event\OrderOnCreatedEvent;
use App\Event\OrderOnUpdatedEvent;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly final class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderProductRepository $orderProductRepository,
        protected OrderStatusRepository $orderStatusRepository,
        protected EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $eventDispatcher
    ) {}

    public function list(User $user, $page = 1, $limit = 10): array
    {
        return $this->orderRepository->getOrderList($user, $page, $limit);
    }

    public function count(User $user): int
    {
        return $this->orderRepository->getOrdersCount($user);
    }

    public function item(User $user, int $id): ?Order
    {
        return $this->orderRepository->getOrderItem($user, $id);
    }

    public function itemById(int $id): ?Order
    {
        return $this->orderRepository->getOrderItemById($id);
    }

    public function create(User $user, string $phone, OrderShipmentMethod $shipmentMethod): ?Order
    {
        $order = $this->createOrder($user, $phone, $shipmentMethod);

        if ($order) {
            $this->eventDispatcher->dispatch(new OrderOnCreatedEvent($order), OrderOnCreatedEvent::NAME);
        }

        return $order;
    }

    public function addProduct(Order $order, Basket $basketItem): OrderProduct
    {
        $orderItem = $this->addOrderItem($order, $basketItem);

        if ($orderItem) {
            $this->eventDispatcher->dispatch(new OrderItemOnAddedEvent($orderItem), OrderItemOnAddedEvent::NAME);
        }

        return $orderItem;
    }

    public function updateStatus(Order $order, OrderStatus $status): Order
    {
        $order = $this->updateOrderStatus($order, $status);

        if ($order) {
            $this->eventDispatcher->dispatch(new OrderOnUpdatedEvent($order), OrderOnUpdatedEvent::NAME);
        }

        return $order;
    }

    protected function createOrder(User $user, string $phone, OrderShipmentMethod $shipmentMethod, OrderStatus $status = null): ?Order
    {
        if (is_null($status)) {
            $status = $this->orderStatusRepository->find(OrderStatus::DEFAULT_ID);
        }

        $order = new Order();

        $order->setUser($user);
        $order->setPhone($phone);
        $order->setShipmentMethod($shipmentMethod);
        $order->setStatus($status);

        $this->entityManager->persist($order);

        return $order;
    }

    protected function addOrderItem(Order $order, Basket $basketItem): ?OrderProduct
    {
        $orderItem = new OrderProduct();

        $orderItem->setOrder($order);
        $orderItem->setProduct($basketItem->getProduct());
        $orderItem->setAmount($basketItem->getAmount());
        $orderItem->setCost($basketItem->getProduct()->getCost());
        $orderItem->setTax($basketItem->getProduct()->getTax());

        $this->entityManager->persist($orderItem);

        return $orderItem;
    }

    protected function updateOrderStatus(Order $order, OrderStatus $status): Order
    {
        $order->setStatus($status);

        $this->entityManager->persist($order);

        return $order;
    }

    public function execute(): void
    {
        $this->entityManager->flush();
    }
}
