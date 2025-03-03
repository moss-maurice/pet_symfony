<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsList($page = 1, $limit = 10): array
    {
        $limit = min(max($limit, 1), 100);

        return $this->findBy([], [], $limit, ($page - 1) * $limit);
    }

    public function getProductsCount(): int
    {
        return $this->count();
    }

    public function getProduct($id): Product|null
    {
        return $this->find($id);
    }

    public function hasProduct(int $id): bool
    {
        return $this->getProduct($id) !== null;
    }
}
