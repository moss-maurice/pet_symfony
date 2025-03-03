<?php

namespace App\Event;

use App\Entity\Order;
use App\Entity\OrderShipmentMethod;
use App\Entity\OrderStatus;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class OrderOnCreatedEvent extends Event
{
    public const NAME = 'order.created';

    public function __construct(
        private readonly Order $order
    ) {
        // Do nothing!
    }

    public function getId(): int
    {
        return $this->order->getId();
    }

    public function getUser(): User
    {
        return $this->order->getUser();
    }

    public function getStatus(): OrderStatus
    {
        return $this->order->getStatus();
    }

    public function getShipmentMethod(): OrderShipmentMethod
    {
        return $this->order->getShipmentMethod();
    }
}
