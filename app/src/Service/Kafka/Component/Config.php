<?php

namespace App\Service\Kafka\Component;

use App\Service\Kafka\Interface\StorageInterface;

final class Config implements StorageInterface
{
    public function __construct(private array $storage = [])
    {
        $this->storage = [];
    }

    public function set(string $key, $value): self
    {
        $this->storage[$key] = $value;

        return $this;
    }

    public function get(string $key, $default = null): mixed
    {
        return \array_key_exists($key, $this->storage) ? $this->storage[$key] : $default;
    }

    public function keys(): array
    {
        return array_keys($this->storage);
    }

    public function walk(callable $callback): self
    {
        if (is_callable($callback) and !empty($this->storage)) {
            foreach ($this->storage as $key => $value) {
                $callback($key, $value);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return $this->storage;
    }

    public function toJson(): string
    {
        return json_encode($this->storage);
    }
}