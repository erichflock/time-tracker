<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:start')]
final class StartWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private WorkdayService $workdayService,
        private DateTimeInputParser $dateTimeInputParser,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)')
        ->addArgument('startTime', InputArgument::OPTIONAL, 'The start time of the workday');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $date = $this->dateTimeInputParser->parseDate($input->getArgument('date'));
        $startTime = $this->dateTimeInputParser->parseTime($input->getArgument('startTime'), 'start time');

        $this->workdayService->startWorkday($date, $startTime);

        return Command::SUCCESS;
    }
}
