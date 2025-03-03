<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class BasketOnDropedEvent extends Event
{
    public const NAME = 'basket.droped';

    public function __construct(
        private readonly User $user
    ) {
        // Do nothing!
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
