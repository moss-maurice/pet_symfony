<?php

namespace App\Service\Console;

use App\Request\UpdateOrderStatusRequest;
use App\Service\KafkaService;
use App\Service\OrderService;
use App\Service\OrderStatusService;
use App\Service\ProductService;
use Carbon\Carbon;
use Exception;
use RdKafka\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class ConsumeService
{
    public function __construct(
        protected KafkaService $kafkaService,
        protected OrderService $orderService,
        protected OrderStatusService $orderStatusService,
        protected ProductService $productService,
        protected SerializerInterface $serializer
    ) {}

    public function consumeOrdersStatuses(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $topic = $input->getArgument('topic');
        $supervisor = $input->getOption('supervisor');

        $this->kafkaService->consumer()
            ->loop($topic, function (Message $message) use ($io): bool {
                if ($message->err === \RD_KAFKA_RESP_ERR_NO_ERROR) {
                    try {
                        $data = $this->serializer->deserialize($message->payload, UpdateOrderStatusRequest::class, JsonEncoder::FORMAT);

                        if (!$data->getOrder()) {
                            throw new Exception("Order not received!");
                        }

                        $order = $this->orderService->itemById($data->getOrder());

                        if (!$order) {
                            throw new Exception("Order {$data->getOrder()} not found!");
                        }

                        if (!$data->getStatus()) {
                            throw new Exception("Status not received!");
                        }

                        $status = $this->orderStatusService->item($data->getStatus());

                        if (!$status) {
                            throw new Exception("Status {$data->getStatus()} not found!");
                        }

                        $this->orderService->updateStatus($order, $status);

                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > Success update order status from kafka!");

                        return true;
                    } catch (Exception $exception) {
                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());
                    }
                }

                return false;
            });

        $this->orderService->execute();

        if (!$supervisor) {
            $io->success("Done!");
        }

        return Command::SUCCESS;
    }

    public function consumeProducts(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $topic = $input->getArgument('topic');
        $supervisor = $input->getOption('supervisor');

        $count = 0;

        $this->kafkaService->consumer()
            ->loop($topic, function (Message $message) use ($io, &$count): bool {
                if ($message->err === \RD_KAFKA_RESP_ERR_NO_ERROR) {
                    try {
                        $this->productService->createFromJson($message->payload);

                        $count++;

                        return true;
                    } catch (Exception $exception) {
                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());
                    }
                }

                return false;
            });

        $this->productService->execute();

        if ($count) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > Success importing $count products from kafka!");

            return Command::SUCCESS;
        }

        if (!$supervisor && !$count) {
            $io->note("No products imported from kafka!");
        }

        return Command::FAILURE;
    }
}
