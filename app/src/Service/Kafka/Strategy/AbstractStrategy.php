<?php

namespace App\Service\Kafka\Strategy;

use App\Service\Kafka\Interface\StorageInterface;
use App\Service\Kafka\Interface\StrategyInterface;
use App\Service\Kafka\Manager;

abstract class AbstractStrategy implements StrategyInterface
{
    protected Manager $manager;

    public function __construct(StorageInterface $config)
    {
        $this->manager = (new Manager)->configure($config->toArray());
    }
}