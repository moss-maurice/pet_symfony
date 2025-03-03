<?php

namespace App\Exception;

use RuntimeException;

class BasketProductAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Product already exists in basket');
    }
}
