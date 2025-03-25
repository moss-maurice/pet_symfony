<?php

namespace App\Command;

use App\Service\Console\UsersService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:create-order-from-basket',
    description: 'Command to creating user order from basket',
)]
class UserCreateOrderFromBasketCommand extends Command
{
    public function __construct(
        readonly private UsersService $usersService
    ) {
        return parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('user', InputArgument::REQUIRED, 'User id')
            ->addArgument('phone', InputArgument::REQUIRED, 'User phone')
            ->addArgument('shipmentMethod', InputArgument::REQUIRED, 'Shipment method id');
    }

    /**
     * cmd: symfony console app:user:create-order-from-basket --user=1 --phone=+79876543210 --shipmentMethod=1
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->usersService->userCreateOrderFromBasket($input, $output);
    }
}
