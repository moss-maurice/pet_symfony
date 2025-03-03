<?php

namespace App\Exception;

use RuntimeException;

class UserAlreadyLoggedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User already logged');
    }
}
