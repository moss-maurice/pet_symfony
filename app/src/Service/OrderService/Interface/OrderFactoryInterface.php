<?php

namespace App\Service\OrderService\Interface;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Entity\User;

interface OrderFactoryInterface
{
    public function list(User $user): array;

    public function get(User $user, int $id): ?Order;

    public function create(User $user, string $phone, OrderShipmentMethod $shipmentMethod): ?Order;

    public function addProduct(Order $order, Basket $basketItem): OrderProduct;

    public function updateStatus(Order $order, OrderStatus $status): Order;
}
