<?php

namespace App\Repository;

use App\Entity\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatus::class);
    }

    public function list(): array
    {
        return $this->findBy([]);
    }

    public function item(int $id): ?OrderStatus
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }
}
