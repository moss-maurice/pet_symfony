<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function getBasketList(User $user): array
    {
        return $this->findBy([
            'user' => $user,
        ]);
    }

    public function getBasketItem(User $user, int $id): ?Basket
    {
        return $this->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);
    }

    public function findBasketItem(User $user, Product $product): ?Basket
    {
        return $this->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);
    }

    public function hasBasketItem(User $user, int $id): bool
    {
        return $this->getBasketItem($user, $id) !== null;
    }

    public function getCarItemsCount(User $user): int
    {
        return count($this->getBasketList($user));
    }
}
