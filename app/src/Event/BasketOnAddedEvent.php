<?php

namespace App\Event;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class BasketOnAddedEvent extends Event
{
    public const NAME = 'basket.added';

    public function __construct(
        private readonly Basket $basketItem
    ) {
        // Do nothing!
    }

    public function getUser(): User
    {
        return $this->basketItem->getUser();
    }

    public function getProduct(): Product
    {
        return $this->basketItem->getProduct();
    }

    public function getAmount(): int
    {
        return $this->basketItem->getAmount();
    }
}
