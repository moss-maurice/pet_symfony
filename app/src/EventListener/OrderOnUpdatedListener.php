<?php

namespace App\EventListener;

use App\Event\OrderOnUpdatedEvent;
use Psr\Log\LoggerInterface;

readonly class OrderOnUpdatedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(OrderOnUpdatedEvent $event): void
    {
        $id = $event->getId();
        $user = $event->getUser();

        $this->logger->info("Updated order id $id for user: {$user->getEmail()}.");
    }
}
