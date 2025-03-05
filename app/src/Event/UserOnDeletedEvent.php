<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserOnDeletedEvent extends Event
{
    public const NAME = 'user.deleted';

    public function __construct(
        readonly private User $user
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }
}
