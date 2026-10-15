<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\JsonWorkdayRepository;

#[AsCommand(name: 'workday:end')]
final class EndWorkdayCommand extends Command
{
    public function __construct(
        private JsonWorkdayRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)', date('d.m.Y'));
        $this->addArgument('endTime', InputArgument::OPTIONAL, 'The end time of the workday', '17:00');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = \DateTimeImmutable::createFromFormat('d.m.Y', $input->getArgument('date'));
        if (!$date) {
            $output->writeln(sprintf('Invalid date format %s', $input->getArgument('date')));
            return Command::FAILURE;
        }

        $endTime = \DateTimeImmutable::createFromFormat('H:i', $input->getArgument('endTime'));
        if (!$endTime) {
            $output->writeln(sprintf('Invalid end time format %s', $input->getArgument('endTime')));
            return Command::FAILURE;
        }   

        $workday = $this->repository->find($date->format('d.m.Y'));
        if (!$workday) {
            $output->writeln(sprintf('Workday not found for date %s', $date->format('d.m.Y')));
            return Command::FAILURE;
        }

        $workday->setEndTime($endTime);
        $this->repository->save($workday);

        return Command::SUCCESS;
    }
}