<?php

namespace App\Service\Kafka\Strategy;

use App\Service\Kafka\Manager;
use App\Service\Kafka\Strategy\AbstractStrategy;
use RdKafka\Message;

class ConsumerStrategy extends AbstractStrategy
{
    public function consume(string $topicName, int $timeout = Manager::BASIC_TIMEOUT): Message
    {
        return $this->manager->receive($topicName, $timeout);
    }

    public function commit(Message $message): void
    {
        $this->manager->commit($message);
    }

    public function loop(string $topicName, callable $callback, int $timeout = Manager::BASIC_TIMEOUT): void
    {
        do {
            $message = $this->consume($topicName, $timeout);

            if (is_callable($callback) and !is_null($callback)) {
                if ($callback($message) === true) {
                    $this->manager->commit($message);
                }
            }
        } while ($message->err === \RD_KAFKA_RESP_ERR_NO_ERROR);
    }
}