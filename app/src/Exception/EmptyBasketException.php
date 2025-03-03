<?php

namespace App\Exception;

use RuntimeException;

class EmptyBasketException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Basket is empty');
    }
}
