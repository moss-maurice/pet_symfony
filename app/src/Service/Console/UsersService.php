<?php

namespace App\Service\Console;

use App\Service\OrderService;
use App\Service\OrderShipmentMethodService;
use App\Service\UserService;
use Carbon\Carbon;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

readonly final class UsersService
{
    public function __construct(
        protected OrderService $orderService,
        protected OrderShipmentMethodService $orderShipmentMethodService,
        protected UserService $userService
    ) {}

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

            $userObject = $this->userService->get($user);

            if (!$userObject) {
                throw new Exception("User not found!");
            }

            $this->userService->grantRole($userObject, $roleName);
            $this->userService->execute();

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }

    public function userCreateOrderFromBasket(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = $input->getArgument('user');
        $phone = $input->getArgument('phone');
        $shipmentMethod = $input->getArgument('shipmentMethod');

        try {
            if (!$user) {
                throw new Exception("User not typed!");
            }

            if (!$phone) {
                throw new Exception("Phone not typed!");
            }

            if (!$shipmentMethod) {
                throw new Exception("Shipment method not typed!");
            }

            $userObject = $this->userService->get($user);

            if (!$userObject) {
                throw new Exception("User not found!");
            }

            $shipmentMethodObject = $this->orderShipmentMethodService->item($shipmentMethod);

            if (!$shipmentMethodObject) {
                throw new Exception("Shipment method not found!");
            }

            $order = $this->orderService->create($userObject, $phone, $shipmentMethodObject);
            $this->orderService->execute();

            if (!$order) {
                throw new Exception("Order not created!");
            }

            $io->success("Success creating new order: " . $order->getid());

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->text(Carbon::now()->format('Y-m-d H:i:s.u') . " > " . $exception->getMessage());

            return Command::FAILURE;
        }
    }
}
