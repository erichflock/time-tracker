<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\JsonWorkdayRepository;
use App\Model\Pause;

#[AsCommand(name: 'workday:add-pause')]
final class AddPauseWorkdayCommand extends Command
{
    public function __construct(
        private JsonWorkdayRepository $repository,
    ) {
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'The date of the workday');
        $this->addArgument('startTime', InputArgument::REQUIRED, 'The start time of the pause');
        $this->addArgument('endTime', InputArgument::REQUIRED, 'The end time of the pause');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = $input->getArgument('date');
        if (!$date) {
            $output->writeln(sprintf('Invalid date format %s', $input->getArgument('date')));
            return Command::FAILURE;
        }

        $startTime = \DateTimeImmutable::createFromFormat('H:i', $input->getArgument('startTime'));
        if (!$startTime) {
            $output->writeln(sprintf('Invalid start time format %s', $input->getArgument('startTime')));
            return Command::FAILURE;
        }

        $endTime = \DateTimeImmutable::createFromFormat('H:i', $input->getArgument('endTime'));
        if (!$endTime) {
            $output->writeln(sprintf('Invalid end time format %s', $input->getArgument('endTime')));
            return Command::FAILURE;
        }

        $workday = $this->repository->find($date);
        if (!$workday) {
            $output->writeln(sprintf('Workday not found for date %s', $date));
            return Command::FAILURE;
        }
        
        $pause = new Pause($startTime, $endTime);
        $workday->addPause($pause);
        $this->repository->save($workday);

        $output->writeln(sprintf('Pause added to workday %s', $date));
        return Command::SUCCESS;
    }
}