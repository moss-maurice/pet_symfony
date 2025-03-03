<?php

namespace App\EventListener;

use App\Event\ProductOnCreatedEvent;
use Psr\Log\LoggerInterface;

class ProductOnCreatedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(ProductOnCreatedEvent $event): void
    {
        $product = $event->getProduct();

        $this->logger->info('Product created: ' . $product->getName());
    }
}
