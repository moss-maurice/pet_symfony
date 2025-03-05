<?php

namespace App\EventListener;

use App\Event\BasketOnDropedEvent;
use Psr\Log\LoggerInterface;

readonly class BasketOnDropedListener
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function __invoke(BasketOnDropedEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->info('Droped basket for user: ' . $user->getEmail() . '.');
    }
}
