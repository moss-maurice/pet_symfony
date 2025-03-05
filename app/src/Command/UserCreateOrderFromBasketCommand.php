<?php

namespace App\Command;

use App\Service\ConsoleService;
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
        readonly private ConsoleService $consoleService
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->consoleService->userCreateOrderFromBasket($input, $output);
    }
}
