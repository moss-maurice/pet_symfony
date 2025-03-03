<?php

namespace App\Exception;

use RuntimeException;

class BasketProductNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Product not found in basket');
    }
}
