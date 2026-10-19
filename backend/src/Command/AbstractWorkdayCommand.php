<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractWorkdayCommand extends Command
{
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            return $this->doExecute($input, $output);
        } catch (\InvalidArgumentException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }

    abstract protected function doExecute(InputInterface $input, OutputInterface $output): int;
}
