<?php

namespace App\Command;

use App\Entity\OrderStatus;
use App\Service\Console\ProduceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:seeding:orders-statuses',
    description: 'Seeding command to produce orders statuses into kafka orders topic',
)]
class SeedingOrdersStatusesCommand extends Command
{
    public function __construct(
        readonly private ParameterBagInterface $parameterBag,
        readonly private ProduceService $produceService
    ) {
        return parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('topic', InputArgument::OPTIONAL, 'Topic name', $this->parameterBag->get('kafka.topic.orders'))
            ->addOption('order', 'o', InputOption::VALUE_REQUIRED, 'Order id')
            ->addOption('status', 's', InputOption::VALUE_OPTIONAL, 'Status id', OrderStatus::DEFAULT_ID);
    }

    /**
     * cmd: symfony console app:seeding:orders-statuses --order=1 --status=2
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->produceService->produceOrdersStatus($input, $output);
    }
}
