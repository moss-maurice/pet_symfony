<?php

namespace App\EventListener;

use App\Event\BasketOnDropedEvent;
use Psr\Log\LoggerInterface;

class BasketOnDropedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(BasketOnDropedEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->info('Droped basket for user: ' . $user->getEmail() . '.');
    }
}
