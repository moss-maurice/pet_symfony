<?php

namespace App\Exception;

use RuntimeException;

class ProductNotFountException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Product not found');
    }
}