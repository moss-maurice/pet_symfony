<?php

namespace App\EventListener;

use App\Event\OrderItemOnAddedEvent;
use Psr\Log\LoggerInterface;

class OrderItemOnAddedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(OrderItemOnAddedEvent $event): void
    {
        $id = $event->getId();
        $order = $event->getOrder();
        $product = $event->getProduct();

        $this->logger->info("Added product '{$product->getName()}' ({$product->getId()}) to order id $id.");
    }
}
