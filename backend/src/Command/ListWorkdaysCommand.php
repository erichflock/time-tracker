<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WorkdayService;

#[AsCommand(name: 'workday:list')]
final class ListWorkdaysCommand extends Command
{
    public function __construct(
        private WorkdayService $workdayService,
    ) {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $workdays = $this->workdayService->findAllWorkdays();
        foreach ($workdays as $workday) {
            $output->writeln(sprintf('%s: %s - %s', $workday->getDate()->format('d.m.Y'), $workday->getStartTime()->format('H:i'), $workday->getEndTime() ? $workday->getEndTime()->format('H:i') : 'N/A'));
            foreach ($workday->getPauses() as $pause) {
                $output->writeln(sprintf('  - %s - %s', $pause->getStartTime()->format('H:i'), $pause->getEndTime()->format('H:i')));
            }
            $output->writeln('');
        }
        return Command::SUCCESS;
    }
}