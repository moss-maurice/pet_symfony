<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductOnCreatedEvent extends Event
{
    public const NAME = 'product.created';

    public function __construct(
        private readonly Product $product
    ) {
        // Do nothing!
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
