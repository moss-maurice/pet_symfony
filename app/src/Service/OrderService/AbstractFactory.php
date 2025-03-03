<?php

namespace App\Service\OrderService;

use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractFactory
{
    public function __construct(
        readonly protected OrderRepository $orderRepository,
        readonly protected OrderStatusRepository $orderStatusRepository,
        readonly protected OrderProductRepository $orderProductRepository,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {}
}
