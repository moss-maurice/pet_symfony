<?php

namespace App\EventListener;

use App\Event\BasketOnAddedEvent;
use Psr\Log\LoggerInterface;

class BasketOnAddedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(BasketOnAddedEvent $event): void
    {
        $user = $event->getUser();
        $product = $event->getProduct();
        $amount = $event->getAmount();

        $this->logger->info('Added ' . $amount . ' pcs of product "' . $product->getName() . '" to basket for user: ' . $user->getEmail() . '.');
    }
}
