<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:add-pause')]
final class AddPauseWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private WorkdayService $workdayService,
        private DateTimeInputParser $dateTimeInputParser,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'The date of the workday');
        $this->addArgument('startTime', InputArgument::REQUIRED, 'The start time of the pause');
        $this->addArgument('endTime', InputArgument::REQUIRED, 'The end time of the pause');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $date = $this->dateTimeInputParser->parseDate($input->getArgument('date'));
        $startTime = $this->dateTimeInputParser->parseTime($input->getArgument('startTime'), 'start time');
        $endTime = $this->dateTimeInputParser->parseTime($input->getArgument('endTime'), 'end time');

        $pause = $this->workdayService->addPause($date, $startTime, $endTime);

        $output->writeln(sprintf('Pause added to workday %s', $this->dateTimeInputParser->formatDate($date)));
        $output->writeln(sprintf('Start time: %s', $this->dateTimeInputParser->formatTime($pause->getStartTime())));
        $output->writeln(sprintf('End time: %s', $this->dateTimeInputParser->formatTime($pause->getEndTime())));
        
        return Command::SUCCESS;
    }
}
