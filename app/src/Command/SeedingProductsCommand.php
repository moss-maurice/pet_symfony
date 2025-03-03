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
    name: 'app:seeding:products',
    description: 'Seeding command to produce fake products into kafka products topic',
)]
class SeedingProductsCommand extends Command
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
            ->addArgument('topic', InputArgument::OPTIONAL, 'Topic name', $this->parameterBag->get('kafka.topic.products'))
            ->addOption('count', 'c', InputOption::VALUE_OPTIONAL, 'Count of messages', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->consoleService->produceProducts($input, $output);
    }
}
