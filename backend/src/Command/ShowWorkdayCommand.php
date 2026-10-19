<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\JsonWorkdayRepository;
use App\Util\DateTimeInputParser;

#[AsCommand(name: 'workday:show')]
final class ShowWorkdayCommand extends AbstractWorkdayCommand
{
    public function __construct(
        private JsonWorkdayRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)', date('d.m.Y'));
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $date = DateTimeInputParser::parseDate($input->getArgument('date'));

        $workday = $this->repository->find($date->format('d.m.Y'));

        if (!$workday) {
            $output->writeln(sprintf('Workday not found for date %s', $date->format('d.m.Y')));
        } else {
            $output->writeln(sprintf('Workday found for date %s', $workday->getDate()->format('d.m.Y')));
            $output->writeln(sprintf('Start time: %s', $workday->getStartTime()->format('H:i')));
        }

        return Command::SUCCESS;
    }
}
