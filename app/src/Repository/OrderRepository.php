<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    protected $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        protected readonly OrderStatusRepository $orderStatusRepository
    ) {
        parent::__construct($registry, Order::class);

        $this->entityManager = $this->getEntityManager();
    }

    public function getOrderList(User $user, $page = 1, $limit = 10): array
    {
        $limit = min(max($limit, 1), 100);

        return $this->findBy([
            'user' => $user,
        ], [], $limit, ($page - 1) * $limit);
    }

    public function getOrdersCount(User $user,): int
    {
        return $this->count([
            'user' => $user,
        ]);
    }

    public function getOrderItem(User $user, int $id): ?Order
    {
        return $this->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);
    }

    public function getOrderItemById(int $id): ?Order
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }

    public function createOrder(User $user, string $phone, OrderShipmentMethod $shipmentMethod, OrderStatus $status = null): ?Order
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
        $this->entityManager->flush();

        return $order;
    }

    public function addOrderItem(Order $order, Basket $basketItem): ?OrderProduct
    {
        $orderItem = new OrderProduct();

        $orderItem->setOrder($order);
        $orderItem->setProduct($basketItem->getProduct());
        $orderItem->setAmount($basketItem->getAmount());
        $orderItem->setCost($basketItem->getProduct()->getCost());
        $orderItem->setTax($basketItem->getProduct()->getTax());

        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();

        return $orderItem;
    }

    public function updateOrderStatus(Order $order, OrderStatus $status): Order
    {
        $order->setStatus($status);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
