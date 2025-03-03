<?php

namespace App\Exception;

use RuntimeException;

class BasketProductsLimitReachedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Products limit reached in basket');
    }
}
