<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductOnCreatedEvent extends Event
{
    public const NAME = 'product.created';

    public function __construct(
        readonly private Product $product
    ) {}

    public function getProduct(): Product
    {
        return $this->product;
    }
}
