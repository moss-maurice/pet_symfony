<?php

namespace App\EventListener;

use App\Event\UserOnDeletedEvent;
use Psr\Log\LoggerInterface;

class UserOnDeletedListener
{
    public function __construct(
        readonly private LoggerInterface $logger
    ) {
        // Do nothing!
    }

    public function __invoke(UserOnDeletedEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->info('User deleted: ' . $user->getEmail());
    }
}
