<?php

namespace App\EventListener;

use App\Event\BasketOnDeletedEvent;
use Psr\Log\LoggerInterface;

class BasketOnDeletedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(BasketOnDeletedEvent $event): void
    {
        $user = $event->getUser();
        $product = $event->getProduct();

        $this->logger->info('Deleted product "' . $product->getName() . '" from basket for user: ' . $user->getEmail() . '.');
    }
}
