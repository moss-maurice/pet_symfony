<?php

namespace App\EventListener;

use App\Event\BasketOnUpdatedEvent;
use Psr\Log\LoggerInterface;

readonly class BasketOnUpdatedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(BasketOnUpdatedEvent $event): void
    {
        $user = $event->getUser();
        $product = $event->getProduct();
        $amount = $event->getAmount();

        $this->logger->info('Updated product "' . $product->getName() . '" to ' . $amount . ' pcs in basket for user: ' . $user->getEmail() . '.');
    }
}
