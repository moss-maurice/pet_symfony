<?php

namespace App\Service\Kafka\Interface;

interface StorageInterface
{
    public function set(string $key, $value): self;

    public function get(string $key, $default = null): mixed;

    public function toArray(): array;

    public function toJson(): string;
}