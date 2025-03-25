<?php

namespace App\Service;

use App\Entity\OrderShipmentMethod;
use App\Repository\OrderShipmentMethodRepository;

readonly final class OrderShipmentMethodService
{
    public function __construct(
        protected OrderShipmentMethodRepository $repository
    ) {}

    public function list(): array
    {
        return $this->repository->list();
    }

    public function item(int $id): ?OrderShipmentMethod
    {
        return $this->repository->item($id);
    }
}
