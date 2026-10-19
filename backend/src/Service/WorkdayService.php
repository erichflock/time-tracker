<?php

namespace App\Service;

use App\Repository\JsonWorkdayRepository;
use App\Model\Workday;
use Symfony\Component\Console\Output\OutputInterface;

final class WorkdayService
{
    public function __construct(
        private JsonWorkdayRepository $repository,
    ) {
    }

    public function startWorkday(\DateTimeImmutable $date, \DateTimeImmutable $startTime): void
    {
        $workday = new Workday($date, $startTime, null, []);
        $this->repository->save($workday);
    }

    public function endWorkday(\DateTimeImmutable $date, \DateTimeImmutable $endTime): void
    {
        $workday = $this->repository->find($date->format('d.m.Y'));
        if (!$workday) {
            throw new \Exception('Workday not found');
        }

        $workday->setEndTime($endTime);
        $this->repository->save($workday);
    }

    public function findWorkday(\DateTimeImmutable $date): Workday
    {
        $workday = $this->repository->find($date->format('d.m.Y'));
        if (!$workday) {
            throw new \Exception('Workday not found');
        }

        return $workday;
    }

    public function findAllWorkdays(): array
    {
        return $this->repository->findAll();
    }
}