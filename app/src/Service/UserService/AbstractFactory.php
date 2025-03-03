<?php

namespace App\Service\UserService;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractFactory
{
    public function __construct(
        readonly protected UserRepository $userRepository,
        readonly protected TokenStorageInterface $tokenStorage,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {}
}
