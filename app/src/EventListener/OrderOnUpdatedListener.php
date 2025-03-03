<?php

namespace App\EventListener;

use App\Event\OrderOnUpdatedEvent;
use Psr\Log\LoggerInterface;

class OrderOnUpdatedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(OrderOnUpdatedEvent $event): void
    {
        $id = $event->getId();
        $user = $event->getUser();

        $this->logger->info("Updated order id $id for user: {$user->getEmail()}.");
    }
}
