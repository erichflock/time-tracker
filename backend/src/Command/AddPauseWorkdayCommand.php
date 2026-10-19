<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;
use App\Model\Pause;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:add-pause')]
final class AddPauseWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private WorkdayService $workdayService,
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
        $date = DateTimeInputParser::parseDate($input->getArgument('date'));
        $startTime = DateTimeInputParser::parseTime($input->getArgument('startTime'), 'start time');
        $endTime = DateTimeInputParser::parseTime($input->getArgument('endTime'), 'end time');

        $pause = $this->workdayService->addPause($date, $startTime, $endTime);

        $output->writeln(sprintf('Pause added to workday %s', $date->format('d.m.Y')));
        $output->writeln(sprintf('Start time: %s', $pause->getStartTime()->format('H:i')));
        $output->writeln(sprintf('End time: %s', $pause->getEndTime()->format('H:i')));
        
        return Command::SUCCESS;
    }
}
