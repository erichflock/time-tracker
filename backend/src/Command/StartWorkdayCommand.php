<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Workday;
use App\Repository\JsonWorkdayRepository;
use App\Service\WorkdayService;

#[AsCommand(name: 'workday:start')]
final class StartWorkdayCommand extends Command
{
    public function __construct(
        private JsonWorkdayRepository $repository,
        private WorkdayService $workdayService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)')
        ->addArgument('startTime', InputArgument::OPTIONAL, 'The start time of the workday');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = \DateTimeImmutable::createFromFormat('d.m.Y', $input->getArgument('date'));
        if (!$date) {
            $output->writeln(sprintf('Invalid date format %s', $input->getArgument('date')));
            return Command::FAILURE;
        }

        $startTime = \DateTimeImmutable::createFromFormat('H:i', $input->getArgument('startTime'));
        if (!$startTime) {
            $output->writeln(sprintf('Invalid start time format %s', $input->getArgument('startTime')));
            return Command::FAILURE;
        }

        $this->workdayService->startWorkday($date, $startTime);

        return Command::SUCCESS;
    }
}