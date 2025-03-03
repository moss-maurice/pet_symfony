<?php

namespace App\Service\Generator\Interface;

use App\Entity\Product;
use App\Entity\ProductMeasurement;

interface ProductFactoryInterface
{
    public function createProduct(): Product;
    public function createProductMeasurement(): ProductMeasurement;
}