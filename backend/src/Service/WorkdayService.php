<?php

namespace App\Service;

use App\Repository\JsonWorkdayRepository;
use App\Model\Workday;

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
}