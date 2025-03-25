<?php

namespace App\Service;

use App\Entity\OrderStatus;
use App\Repository\OrderStatusRepository;

readonly final class OrderStatusService
{
    public function __construct(
        protected OrderStatusRepository $repository,
    ) {}

    public function list(): array
    {
        return $this->repository->list();
    }

    public function item(int $id): ?OrderStatus
    {
        return $this->repository->item($id);
    }
}
