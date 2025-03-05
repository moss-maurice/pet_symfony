<?php

namespace App\EventListener;

use App\Event\BasketOnDeletedEvent;
use Psr\Log\LoggerInterface;

readonly class BasketOnDeletedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(BasketOnDeletedEvent $event): void
    {
        $user = $event->getUser();
        $product = $event->getProduct();

        $this->logger->info('Deleted product "' . $product->getName() . '" from basket for user: ' . $user->getEmail() . '.');
    }
}
