<?php

namespace App\Service\Generator;

use App\Service\FakerService;
use Faker\Generator;

abstract class AbstractFactory
{
    readonly protected Generator $faker;

    public function __construct(
        FakerService $faker
    )
    {
        $this->faker = $faker->generator();
    }
}