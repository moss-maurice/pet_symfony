<?php

namespace App\Service\BasketService\Interface;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;

interface BasketFactoryInterface
{
    public function list(User $user): array;

    public function get(User $user, int $id): ?Basket;

    public function find(User $user, Product $product): ?Basket;

    public function has(User $user, int $id): bool;

    public function canAdd(User $user): bool;

    public function add(User $user, Product $product, int $amount): ?Basket;

    public function update(User $user, Basket $basketItem, int $amount): ?Basket;

    public function delete(User $user, Basket $basketItem): bool;

    public function drop(User $user): bool;
}
