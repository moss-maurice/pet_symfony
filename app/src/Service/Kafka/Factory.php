<?php

namespace App\Service\Kafka;

use App\Service\Kafka\Component\Config;
use App\Service\Kafka\Interface\StrategyInterface;
use App\Service\Kafka\Strategy\ConsumerStrategy;
use App\Service\Kafka\Strategy\ProducerStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class Factory
{
    protected Config $config;

    public function __construct(readonly private ParameterBagInterface $parameterBag)
    {
        $this->config = new Config;

        $this->config
            ->set('metadata.broker.list', $this->parameterBag->get('kafka.broker'));
    }

    public function createConsumer(): StrategyInterface
    {
        $this->config
            ->set('enable.auto.commit', $this->parameterBag->get('kafka.enableAutoCommit'))
            ->set('group.id', $this->parameterBag->get('kafka.consumerGroupId'))
            ->set('auto.offset.reset', $this->parameterBag->get('kafka.autoOffsetReset'));

        return new ConsumerStrategy($this->config);
    }

    public function createProducer(): StrategyInterface
    {
        return new ProducerStrategy($this->config);
    }
}
