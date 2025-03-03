<?php

namespace App\Command;

use App\Service\ConsoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:consume:orders-statuses',
    description: 'Command to consume orders statuses from kafka orders topic',
)]
class ConsumeOrdersStatusesCommand extends Command
{
    public function __construct(
        readonly private ParameterBagInterface $parameterBag,
        readonly private ConsoleService $consoleService
    ) {
        return parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('topic', InputArgument::OPTIONAL, 'Topic name', $this->parameterBag->get('kafka.topic.orders'))
            ->addOption('supervisor', 's', InputOption::VALUE_NONE, 'Logs output supervisor style');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->consoleService->consumeOrdersStatuses($input, $output);
    }
}
