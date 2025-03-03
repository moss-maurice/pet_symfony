<?php

namespace App\Exception;

use RuntimeException;

class UserNotCreatedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('User is not created');
    }
}
