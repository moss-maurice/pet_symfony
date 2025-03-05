<?php

namespace App\Service\OrderService;

use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Service\OrderService\AbstractCatalogFactory;
use App\Service\OrderService\Interface\OrderCatalogFactoryInterface;

readonly final class OrderCatalogBuilderFactory extends AbstractCatalogFactory implements OrderCatalogFactoryInterface
{
    public function shipmentsMethodsList(): array
    {
        return $this->orderShipmentMethodusRepository->list();
    }

    public function shipmentsMethodsItem(int $id): ?OrderShipmentMethod
    {
        return $this->orderShipmentMethodusRepository->item($id);
    }

    public function statusesList(): array
    {
        return $this->orderStatusRepository->list();
    }

    public function statusesItem(int $id): ?OrderStatus
    {
        return $this->orderStatusRepository->item($id);
    }
}
