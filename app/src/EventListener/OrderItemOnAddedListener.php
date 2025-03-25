<?php

namespace App\EventListener;

use App\Event\OrderItemOnAddedEvent;
use Psr\Log\LoggerInterface;

readonly class OrderItemOnAddedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(OrderItemOnAddedEvent $event): void
    {
        $product = $event->getProduct();

        $this->logger->info("Added product '{$product->getName()}' to order.");
    }
}
