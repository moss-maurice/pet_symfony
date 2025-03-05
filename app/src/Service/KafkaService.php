<?php

namespace App\Service;

use App\Service\Kafka\Factory;
use App\Service\Kafka\Strategy\ConsumerStrategy;
use App\Service\Kafka\Strategy\ProducerStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly final class KafkaService
{
    protected Factory $factory;

    public function __construct(
        protected ParameterBagInterface $parameterBag
    ) {
        $this->factory = new Factory($this->parameterBag);
    }

    public function consumer(): ConsumerStrategy
    {
        return $this->factory->createConsumer();
    }

    public function producer(): ProducerStrategy
    {
        return $this->factory->createProducer();
    }
}
