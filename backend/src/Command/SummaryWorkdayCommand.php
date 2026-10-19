<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\JsonWorkdayRepository;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:summary')]
final class SummaryWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private JsonWorkdayRepository $repository,
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

        $dateKey = $date->format('d.m.Y');
        $workday = $this->repository->find($dateKey);
        if (!$workday) {
            $output->writeln(sprintf('Workday not found for date %s', $dateKey));
            return Command::FAILURE;
        }

        $pausesMinutes = 0;
        foreach ($workday->getPauses() as $pause) {
            $pauseInterval = $pause->getStartTime()->diff($pause->getEndTime());
            $pausesMinutes += ($pauseInterval->h * 60) + $pauseInterval->i;
        }

        $endTime = $workday->getEndTime();
        if ($endTime) {
            $workedInterval = $workday->getStartTime()->diff($endTime);
            $totalMinutes = ($workedInterval->h * 60) + $workedInterval->i - $pausesMinutes;
            $output->writeln(sprintf(
                'Total time for workday %s: %02d:%02d hours',
                $dateKey,
                intdiv($totalMinutes, 60),
                $totalMinutes % 60
            ));
        } else {
            $expectedEndTime = $workday->getStartTime()
                ->modify('+8 hours')
                ->add(new \DateInterval(sprintf('PT%dM', $pausesMinutes)));
            $output->writeln(sprintf('Expected end time for workday %s: %s', $dateKey, $expectedEndTime->format('H:i')));
        }

        return Command::SUCCESS;
    }
}
