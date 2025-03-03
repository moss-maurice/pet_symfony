<?php

namespace App\Service\Kafka;

use App\Service\Kafka\Component\Headers;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use RdKafka\Producer;
use RdKafka\ProducerTopic;
use RuntimeException;

class Manager
{
    const BASIC_TIMEOUT = 10000;

    protected $config;
    protected $consumer;
    protected $producer;
    protected $producerTopics;
    protected $consumerTopics;

    public function __construct()
    {
        $this->config = new Conf;
    }

    public function __destruct()
    {
        if (!is_null($this->consumer)) {
            $this->consumer->close();
        }
    }

    public function configure(array $config = []): self
    {
        if (is_array($config) and !empty($config)) {
            foreach ($config as $key => $value) {
                $this->config->set($key, $value);
            }
        }

        return $this;
    }

    public function transmit(string $topicName, string $message, int $partition = RD_KAFKA_PARTITION_UA, string $key = null, Headers $headers = null, int $timeout = self::BASIC_TIMEOUT): int
    {
        $this->makeTopic($topicName)
            ->producev($partition, 0, $message, $key, $headers);

        $code = $this->producer->flush($timeout);

        if ($code === RD_KAFKA_RESP_ERR_NO_ERROR) {
            return $code;
        }

        throw new RuntimeException($message, $code ?? RD_KAFKA_RESP_ERR_UNKNOWN);
    }

    public function receive(string $topicName, int $timeout = self::BASIC_TIMEOUT): Message
    {
        $this->makeConsumer();
        $this->subscribeConsumer($topicName);

        return $this->consumer->consume($timeout);
    }

    public function commit(Message $message): void
    {
        $this->consumer->commit($message);
    }

    protected function makeProducer(): void
    {
        if (is_null($this->producer)) {
            $this->producer = new Producer($this->config);

            $this->producerTopics = [];
        }
    }

    protected function makeConsumer(): void
    {
        if (is_null($this->consumer)) {
            $this->consumer = new KafkaConsumer($this->config);

            $this->consumerTopics = [];
        }
    }

    protected function subscribeConsumer(string $topicName): void
    {
        $this->makeConsumer();

        if (!in_array($topicName, $this->consumerTopics)) {
            $this->consumerTopics[] = $topicName;

            $this->consumer->subscribe([$topicName]);
        }
    }

    protected function makeTopic(string $topicName): ProducerTopic
    {
        $this->makeProducer();

        if (!isset($this->producerTopics[$topicName])) {
            $this->producerTopics[$topicName] = $this->producer->newTopic($topicName);
        }

        return $this->producerTopics[$topicName];
    }
}
