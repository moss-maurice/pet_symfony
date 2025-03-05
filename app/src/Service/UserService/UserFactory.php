<?php

namespace App\Service\UserService;

use App\Entity\User;
use App\Event\UserOnDeletedEvent;
use App\Event\UserOnRegisteredEvent;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotCreatedException;
use App\Service\UserService\AbstractFactory;
use App\Service\UserService\Interface\UserFactoryInterface;

readonly final class UserFactory extends AbstractFactory implements UserFactoryInterface
{
    public function create(string $name, string $email, string $phone, string $password): User
    {
        if ($this->hasByEmail($email)) {
            throw new UserAlreadyExistsException;
        }

        $user = $this->userRepository->createUser($name, $email, $phone, $password);

        if (!$user) {
            throw new UserNotCreatedException;
        }

        return $user;
    }

    public function register(string $name, string $email, string $phone, string $password): User
    {
        $user = $this->create($name, $email, $phone, $password);

        $this->eventDispatcher->dispatch(new UserOnRegisteredEvent($user), UserOnRegisteredEvent::NAME);

        return $user;
    }

    public function has(int $id): bool
    {
        return $this->userRepository->hasUser($id);
    }

    public function hasByName(string $name): bool
    {
        return $this->userRepository->hasUserBy([
            'name' => $name,
        ]);
    }

    public function hasByEmail(string $email): bool
    {
        return $this->userRepository->hasUserBy([
            'email' => $email,
        ]);
    }

    public function hasByPhone(string $phone): bool
    {
        return $this->userRepository->hasUserBy([
            'phone' => $phone,
        ]);
    }

    public function get(int $id): User
    {
        return $this->userRepository->getUser($id);
    }

    public function getByName(string $name): User
    {
        return $this->userRepository->getUserBy([
            'name' => $name,
        ]);
    }

    public function getByEmail(string $email): User
    {
        return $this->userRepository->getUserBy([
            'email' => $email,
        ]);
    }

    public function getByPhone(string $phone): User
    {
        return $this->userRepository->getUserBy([
            'phone' => $phone,
        ]);
    }

    public function delete(int $id): bool
    {
        $user = $this->get($id);

        if ($user) {
            $this->userRepository->delete($user);

            $this->eventDispatcher->dispatch(new UserOnDeletedEvent($user), UserOnDeletedEvent::NAME);

            return !$this->has($id);
        }

        return false;
    }

    public function deleteByName(string $name): bool
    {
        $user = $this->getByName($name);

        if ($user) {
            $this->userRepository->delete($user);

            return !$this->hasByName($name);
        }

        return false;
    }

    public function deleteByEmail(string $email): bool
    {
        $user = $this->getByEmail($email);

        if ($user) {
            $this->userRepository->delete($user);

            return !$this->hasByEmail($email);
        }

        return false;
    }

    public function deleteByPhone(string $phone): bool
    {
        $user = $this->getByPhone($phone);

        if ($user) {
            $this->userRepository->delete($user);

            return !$this->hasByPhone($phone);
        }

        return false;
    }

    public function isLoggedUser(): bool
    {
        return $this->loggedUser() !== null;
    }

    public function loggedUser(): ?User
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }

    public function grantRole(User $user, string $role): User
    {
        return $this->userRepository->grantUser($user, $role);
    }
}
