<?php

namespace App\Service\OrderService;

use App\Repository\OrderShipmentMethodRepository;
use App\Repository\OrderStatusRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractCatalogFactory
{
    public function __construct(
        readonly protected OrderShipmentMethodRepository $orderShipmentMethodusRepository,
        readonly protected OrderStatusRepository $orderStatusRepository
    ) {}
}
