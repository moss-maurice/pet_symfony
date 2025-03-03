<?php

namespace App\Service\UserService\Interface;

use App\Entity\User;

interface UserFactoryInterface
{
    public function create(string $name, string $email, string $phone, string $password): User;

    public function register(string $name, string $email, string $phone, string $password): User;

    public function has(int $id): bool;

    public function hasByName(string $name): bool;

    public function hasByEmail(string $email): bool;

    public function hasByPhone(string $phone): bool;

    public function get(int $id): User;

    public function getByName(string $name): User;

    public function getByEmail(string $email): User;

    public function getByPhone(string $phone): User;

    public function delete(int $id): bool;

    public function deleteByName(string $name): bool;

    public function deleteByEmail(string $email): bool;

    public function deleteByPhone(string $phone): bool;
}
