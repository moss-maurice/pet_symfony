<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
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
}
