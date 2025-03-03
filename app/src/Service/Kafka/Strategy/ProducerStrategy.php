<?php

namespace App\Service\Kafka\Strategy;

use App\Service\Kafka\Component\Headers;
use App\Service\Kafka\Manager;
use App\Service\Kafka\Strategy\AbstractStrategy;

class ProducerStrategy extends AbstractStrategy
{
    public function produce(string $topicName, string $message, int $partition = \RD_KAFKA_PARTITION_UA, string $key = null, Headers $headers = null, int $timeout = Manager::BASIC_TIMEOUT): bool
    {
        return $this->manager->transmit($topicName, $message, $partition, $key, $headers, $timeout) === RD_KAFKA_RESP_ERR_NO_ERROR;
    }

    public function loop(string $topicName, array $messages, int $partition = \RD_KAFKA_PARTITION_UA, string $key = null, Headers $headers = null, int $timeout = Manager::BASIC_TIMEOUT): void
    {
        foreach ($messages as $message) {
            $this->produce($topicName, $message, $partition, $key, $headers, $timeout);
        }
    }
}