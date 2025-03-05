<?php

namespace App\Service;

use App\Entity\Product;
use App\Service\Generator\ProductFactory;
use Doctrine\Common\Collections\ArrayCollection;

readonly final class GeneratorService
{
    protected ProductFactory $factory;

    public function __construct(
        protected FakerService $faker
    ) {
        $this->factory = new ProductFactory($this->faker);
    }

    public function product(): Product
    {
        return $this->factory->createProduct();
    }

    public function products(int $count): ArrayCollection
    {
        $products = new ArrayCollection;

        for ($i = 0; $i < $count; $i++) {
            $product = $this->product();

            $products->add($product);
        }

        return $products;
    }
}
