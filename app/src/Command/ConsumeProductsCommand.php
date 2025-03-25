<?php

namespace App\Command;

use App\Service\Console\ConsumeService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:consume:products',
    description: 'Command to consume products from kafka products topic',
)]
class ConsumeProductsCommand extends Command
{
    public function __construct(
        readonly private ParameterBagInterface $parameterBag,
        readonly private ConsumeService $consumeService
    ) {
        return parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('topic', InputArgument::OPTIONAL, 'Topic name', $this->parameterBag->get('kafka.topic.products'))
            ->addOption('supervisor', 's', InputOption::VALUE_NONE, 'Logs output supervisor style');
    }

    /**
     * cmd: symfony console app:seeding:products --count=1000
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->consumeService->consumeProducts($input, $output);
    }
}
