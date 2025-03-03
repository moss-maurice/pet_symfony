<?php

namespace App\EventListener;

use App\Event\OrderOnCreatedEvent;
use Psr\Log\LoggerInterface;

class OrderOnCreatedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(OrderOnCreatedEvent $event): void
    {
        $id = $event->getId();
        $user = $event->getUser();

        $this->logger->info("Created order id $id for user: {$user->getEmail()}.");
    }
}
