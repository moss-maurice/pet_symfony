<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BasketRepository extends ServiceEntityRepository
{
    protected $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);

        $this->entityManager = $this->getEntityManager();
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

    public function addBasketItem(User $user, Product $product, int $amount): Basket
    {
        $basketItem = new Basket();

        $basketItem->setUser($user);
        $basketItem->setProduct($product);
        $basketItem->setAmount($amount);

        $this->entityManager->persist($basketItem);
        $this->entityManager->flush();

        return $basketItem;
    }

    public function updateBasketItem(User $user, Basket $basketItem, int $amount): ?Basket
    {
        if ($basketItem->getUser() !== $user) {
            return null;
        }

        $basketItem->setAmount($amount);

        $this->entityManager->persist($basketItem);
        $this->entityManager->flush();

        return $basketItem;
    }

    public function deleteBasketItem(User $user, Basket $basketItem): bool
    {
        if ($basketItem->getUser() !== $user) {
            return false;
        }

        $id = $basketItem->getId();

        $this->entityManager->remove($basketItem);
        $this->entityManager->flush();

        return !$this->hasBasketItem($user, $id);
    }

    public function dropBasket(User $user): bool
    {
        $list = $this->getBasketList($user);

        if (is_array($list) and !empty($list)) {
            foreach ($list as $basketItem) {
                $this->entityManager->remove($basketItem);
                $this->entityManager->flush();
            }
        }

        return $this->getCarItemsCount($user) > 0 ? false : true;
    }
}
