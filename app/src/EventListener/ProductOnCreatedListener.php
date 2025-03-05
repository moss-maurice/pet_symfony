<?php

namespace App\EventListener;

use App\Event\ProductOnCreatedEvent;
use Psr\Log\LoggerInterface;

readonly class ProductOnCreatedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(ProductOnCreatedEvent $event): void
    {
        $product = $event->getProduct();

        $this->logger->info('Product created: ' . $product->getName());
    }
}
