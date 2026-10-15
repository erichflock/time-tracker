<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\Workday;
use App\Repository\JsonWorkdayRepository;

#[AsCommand(name: 'workday:start')]
final class StartWorkdayCommand extends Command
{
    public function __construct(
        private JsonWorkdayRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->addArgument('date', InputArgument::OPTIONAL, 'The date of the workday (d.m.Y)', date('d.m.Y'))
        ->addArgument('startTime', InputArgument::OPTIONAL, 'The start time of the workday', '09:00');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = \DateTimeImmutable::createFromFormat('d.m.Y', $input->getArgument('date'));
        $startTime = \DateTimeImmutable::createFromFormat('H:i', $input->getArgument('startTime'));

        $workday = new Workday($date, $startTime);

        #$output->writeln(sprintf('Workday started at %s on %s', $workday->getTime()->format('H:i'), $workday->getDate()->format('d.m.Y')));
        $this->repository->save($workday);

        return Command::SUCCESS;
    }
}