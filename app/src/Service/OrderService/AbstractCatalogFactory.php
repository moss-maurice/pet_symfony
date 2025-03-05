<?php

namespace App\Service\OrderService;

use App\Repository\OrderShipmentMethodRepository;
use App\Repository\OrderStatusRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly abstract class AbstractCatalogFactory
{
    public function __construct(
        protected OrderShipmentMethodRepository $orderShipmentMethodusRepository,
        protected OrderStatusRepository $orderStatusRepository
    ) {}
}
