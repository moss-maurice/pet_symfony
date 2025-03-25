<?php

namespace App\Service\Console;

use App\Service\KafkaService;
use App\Service\ProductService;
use Carbon\Carbon;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class ProduceService
{
    public function __construct(
        protected KafkaService $kafkaService,
        protected ProductService $productService,
        protected SerializerInterface $serializer
    ) {}

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
                $product = $this->productService->makeProduct();

                if ($product) {
                    $message = $this->serializer->serialize($product, JsonEncoder::FORMAT, [
                        'groups' => ['generator'],
                    ]);

                    $this->kafkaService->producer()->produce($topic, $message);
                }
            }

            $io->success("Success sending $count products into kafka!");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
