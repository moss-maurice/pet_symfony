<?php

namespace App\Service;

use App\Exception\UserAlreadyLoggedException;
use App\Repository\UserRepository;
use App\Request\UserRegisterRequest;
use App\Service\UserService\UserFactory;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class UserService
{
    readonly protected UserFactory $factory;

    public function __construct(
        readonly protected UserRepository $userRepository,
        readonly protected TokenStorageInterface $tokenStorage,
        readonly protected EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = new UserFactory($this->userRepository, $this->tokenStorage, $this->eventDispatcher);
    }

    public function factory()
    {
        return $this->factory;
    }

    public function createUser(UserRegisterRequest $request): JsonResponse
    {
        $this->factory->create($request->getName(), $request->getEmail(), $request->getPhone(), $request->getPassword());

        return new JsonResponse([
            'message' => 'User created successfully',
        ], JsonResponse::HTTP_CREATED);
    }

    public function registerUser(UserRegisterRequest $request): JsonResponse
    {
        if ($this->factory->isLoggedUser()) {
            throw new UserAlreadyLoggedException;
        }

        $this->factory->register($request->getName(), $request->getEmail(), $request->getPhone(), $request->getPassword());

        return new JsonResponse([
            'message' => 'User created successfully',
        ], JsonResponse::HTTP_CREATED);
    }

    public function isLoggedUser(): JsonResponse
    {
        try {
            $logged = $this->factory->isLoggedUser();
        } catch (ExpiredTokenException $exception) {
            return new JsonResponse([
                'logged' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode());
        }

        return new JsonResponse([
            'logged' => $logged,
            'message' => $logged ? 'User logged' : 'User not logged',
        ], JsonResponse::HTTP_CREATED);
    }
}
