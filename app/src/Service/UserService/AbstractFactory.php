<?php

namespace App\Service\UserService;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly abstract class AbstractFactory
{
    public function __construct(
        protected UserRepository $userRepository,
        protected TokenStorageInterface $tokenStorage,
        protected EventDispatcherInterface $eventDispatcher
    ) {}
}
