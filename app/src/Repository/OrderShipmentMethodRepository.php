<?php

namespace App\Repository;

use App\Entity\OrderShipmentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderShipmentMethod>
 */
class OrderShipmentMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderShipmentMethod::class);
    }

    public function list(): array
    {
        return $this->findBy([]);
    }

    public function item(int $id): ?OrderShipmentMethod
    {
        return $this->findOneBy([
            'id' => $id,
        ]);
    }
}
