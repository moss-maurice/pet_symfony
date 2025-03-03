<?php

namespace App\Service\OrderService\Interface;

interface OrderCatalogFactoryInterface
{
    public function shipmentsMethodsList(): array;

    public function statusesList(): array;
}
