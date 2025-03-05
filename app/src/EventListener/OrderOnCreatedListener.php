<?php

namespace App\EventListener;

use App\Event\OrderOnCreatedEvent;
use Psr\Log\LoggerInterface;

readonly class OrderOnCreatedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(OrderOnCreatedEvent $event): void
    {
        $id = $event->getId();
        $user = $event->getUser();

        $this->logger->info("Created order id $id for user: {$user->getEmail()}.");
    }
}
