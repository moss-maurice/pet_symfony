<?php

namespace App\Service\OrderService;

use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly abstract class AbstractFactory
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderStatusRepository $orderStatusRepository,
        protected OrderProductRepository $orderProductRepository,
        protected EventDispatcherInterface $eventDispatcher
    ) {}
}
