<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:summary')]
final class SummaryWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private WorkdayService $workdayService,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'The date of the workday');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $date = DateTimeInputParser::parseDate($input->getArgument('date'));

        $summary = $this->workdayService->getSummary($date);

        if ($summary->totalWorkedTimeInMinutes) {
            $output->writeln(sprintf('Total worked time: %02d:%02d hours', intdiv($summary->totalWorkedTimeInMinutes, 60), $summary->totalWorkedTimeInMinutes % 60));
        } 

        if ($summary->expectedEndTime) {
            $output->writeln(sprintf('Expected end time: %s', $summary->expectedEndTime->format('H:i')));
        }

        return Command::SUCCESS;
    }
}
