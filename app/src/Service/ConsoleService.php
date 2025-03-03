<?php

namespace App\Service;

use App\Request\UpdateOrderStatusRequest;
use App\Service\GeneratorService;
use App\Service\KafkaService;
use App\Service\OrderService;
use App\Service\ProductService;
use App\Service\UserService;
use Carbon\Carbon;
use Exception;
use RdKafka\Message;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class ConsoleService
{
    public function __construct(
        readonly protected KafkaService $kafkaService,
        readonly protected OrderService $orderService,
        readonly protected ProductService $productService,
        readonly protected GeneratorService $generatorService,
        readonly protected UserService $userService,
        readonly protected SerializerInterface $serializer
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

                        $order = $this->orderService->factory()->getById($data->getOrder());

                        if (!$order) {
                            throw new Exception("Order {$data->getOrder()} not found!");
                        }

                        if (!$data->getStatus()) {
                            throw new Exception("Status not received!");
                        }

                        $status = $this->orderService->catalogFactory()->statusesItem($data->getStatus());

                        if (!$status) {
                            throw new Exception("Status {$data->getStatus()} not found!");
                        }

                        $this->orderService->factory()->updateStatus($order, $status);

                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > Success update order status from kafka!");

                        return true;
                    } catch (Exception $exception) {
                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());
                    }
                }

                return false;
            });

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
                        $this->productService->createProductFromJson($message->payload);

                        $count++;

                        return true;
                    } catch (Exception $exception) {
                        $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());
                    }
                }

                return false;
            });

        if ($count) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > Success importing $count products from kafka!");

            return Command::SUCCESS;
        }

        if (!$supervisor && !$count) {
            $io->note("No products imported from kafka!");
        }

        return Command::FAILURE;
    }

    public function produceOrdersStatus(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $topic = $input->getArgument('topic');
        $order = intval($input->getOption('order'));
        $status = intval($input->getOption('status'));

        try {
            if (!$order) {
                throw new Exception("Order not typed!");
            }

            if (!$status) {
                throw new Exception("Status not typed!");
            }

            $message = $this->serializer->serialize([
                'order' => $order,
                'status' => $status,
            ], JsonEncoder::FORMAT);

            $this->kafkaService->producer()->produce($topic, $message);

            $io->success("Success sending status $status for order $order into kafka!");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }

    public function produceProducts(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $topic = $input->getArgument('topic');
        $count = $input->getOption('count');

        try {
            for ($i = 0; $i < \intval($count); $i++) {
                $product = $this->generatorService->product();

                if ($product) {
                    $message = $this->serializer->serialize($product, JsonEncoder::FORMAT, [
                        'groups' => ['generator'],
                    ]);

                    $this->kafkaService->producer()->produce($input->getArgument($topic), $message);
                }
            }

            $io->success("Success sending $count products into kafka!");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }

    public function userRoleGrants(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = $input->getArgument('user');
        $role = $input->getArgument('role');

        try {
            $roleName = 'ROLE_USER';

            switch ($role) {
                case 'admin':
                    $roleName = 'ROLE_ADMIN';

                    break;
                default:
                    break;
            }

            if (!$user) {
                throw new Exception("User not typed!");
            }

            $userObject = $this->userService->factory()->get($user);

            if (!$userObject) {
                throw new Exception("User not found!");
            }

            $this->userService->factory()->grantRole($userObject, $roleName);

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
