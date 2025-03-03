<?php

namespace App\Command;

use App\Service\ConsoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:role',
    description: 'Command to grant user role',
)]
class UserRoleCommand extends Command
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
            ->addArgument('role', InputArgument::REQUIRED, 'User role');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->consoleService->userRoleGrants($input, $output);
    }
}
