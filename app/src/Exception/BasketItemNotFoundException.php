<?php

namespace App\Exception;

use RuntimeException;

class BasketItemNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Basket item not found');
    }
}