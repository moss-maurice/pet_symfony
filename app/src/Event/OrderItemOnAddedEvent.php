<?php

namespace App\Event;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class OrderItemOnAddedEvent extends Event
{
    public const NAME = 'orderItem.added';

    public function __construct(
        private readonly OrderProduct $orderProduct
    ) {
        // Do nothing!
    }

    public function getId(): int
    {
        return $this->orderProduct->getId();
    }

    public function getOrder(): Order
    {
        return $this->orderProduct->getOrder();
    }

    public function getProduct(): Product
    {
        return $this->orderProduct->getProduct();
    }
}
