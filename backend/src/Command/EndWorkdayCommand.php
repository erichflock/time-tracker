<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:end')]
final class EndWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private WorkdayService $workdayService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)', date('d.m.Y'));
        $this->addArgument('endTime', InputArgument::OPTIONAL, 'The end time of the workday', '17:00');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $date = DateTimeInputParser::parseDate($input->getArgument('date'));
        $endTime = DateTimeInputParser::parseTime($input->getArgument('endTime'), 'end time');

        $this->workdayService->endWorkday($date, $endTime);

        return Command::SUCCESS;
    }
}
