<?php

namespace App\Event;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class BasketOnDeletedEvent extends Event
{
    public const NAME = 'basket.deleted';

    public function __construct(
        readonly private Basket $basketItem
    ) {}

    public function getUser(): User
    {
        return $this->basketItem->getUser();
    }

    public function getProduct(): Product
    {
        return $this->basketItem->getProduct();
    }
}
