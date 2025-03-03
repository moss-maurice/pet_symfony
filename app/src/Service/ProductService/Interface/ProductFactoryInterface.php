<?php

namespace App\Service\ProductService\Interface;

use App\Entity\Product;

interface ProductFactoryInterface
{
    public function build(string $json): Product;

    public function list($page = 1, $limit = 10): array;

    public function count(): int;

    public function has(int $id): bool;

    public function get(int $id): Product;
}
