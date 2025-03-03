<?php

namespace App\Exception;

use RuntimeException;

class ShipmentMethodNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Shipment method not found');
    }
}
