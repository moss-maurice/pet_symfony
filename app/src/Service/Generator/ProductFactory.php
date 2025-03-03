<?php

namespace App\Service\Generator;

use App\Entity\Product;
use App\Entity\ProductMeasurement;
use App\Service\Generator\AbstractFactory;
use App\Service\Generator\Interface\ProductFactoryInterface;
use Carbon\Carbon;

final class ProductFactory extends AbstractFactory implements ProductFactoryInterface
{
    public function createProduct(): Product
    {
        $version = Carbon::now()->timestamp;

        $product = new Product;

        $product->setName($this->faker->words(3, true));
        $product->setDescription($this->faker->paragraph(3));
        $product->setCost($this->faker->numberBetween(500, 50000));
        $product->setTax(ceil($product->getCost() * 0.13));
        $product->setVersion($version);

        $measurement = $this->createProductMeasurement();

        if ($measurement) {
            $product->setMeasurements($measurement);
        }

        return $product;
    }

    public function createProductMeasurement(): ProductMeasurement
    {
        $productMeasurement = new ProductMeasurement;

        $productMeasurement->setWeight($this->faker->numberBetween(1, 50));
        $productMeasurement->setLength($this->faker->numberBetween(10, 100));
        $productMeasurement->setWidth($this->faker->numberBetween(10, 100));
        $productMeasurement->setHeight($this->faker->numberBetween(10, 100));

        return $productMeasurement;
    }
}
