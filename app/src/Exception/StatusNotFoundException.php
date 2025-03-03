<?php

namespace App\Exception;

use RuntimeException;

class StatusNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Status not found');
    }
}
