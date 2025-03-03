<?php

namespace App\Exception;

use RuntimeException;

class OrderNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Order not found');
    }
}