<?php

namespace App\Service;

use App\Repository\JsonWorkdayRepository;
use App\Model\Workday;
use App\Model\Pause;
use App\Dto\WorkdaySummary;
use App\Util\DateTimeInputParser;
use App\Util\DateValidator;
use App\Util\TimeValidator;

final class WorkdayService
{
    public function __construct(
        private JsonWorkdayRepository $repository,
        private DateTimeInputParser $dateTimeInputParser,
        private DateValidator $dateValidator,
        private TimeValidator $timeValidator,
    ) {
    }

    public function startWorkday(\DateTimeImmutable $date, \DateTimeImmutable $startTime): void
    {
        $this->dateValidator->assertReasonableWorkdayDate($date);
        $workday = new Workday($date, $startTime, null, []);
        $this->repository->save($workday);
    }

    public function endWorkday(\DateTimeImmutable $date, \DateTimeImmutable $endTime): void
    {
        $workday = $this->repository->find($this->dateTimeInputParser->formatDate($date));
        if (!$workday) {
            throw new \Exception('Workday not found');
        }

        $this->timeValidator->assertReasonableWorkdayEndTime($workday->getStartTime(), $endTime);
        
        $workday->setEndTime($endTime);
        $this->repository->save($workday);
    }

    public function findWorkday(\DateTimeImmutable $date): Workday
    {
        $workday = $this->repository->find($this->dateTimeInputParser->formatDate($date));
        if (!$workday) {
            throw new \Exception('Workday not found');
        }

        return $workday;
    }

    public function findAllWorkdays(): array
    {
        return $this->repository->findAll();
    }

    public function addPause(\DateTimeImmutable $date, \DateTimeImmutable $startTime, \DateTimeImmutable $endTime): Pause
    {
        $workday = $this->findWorkday($date);

        $pause = new Pause($startTime, $endTime);
        $workday->addPause($pause);
        $this->repository->save($workday);

        return $pause;
    }

    public function getSummary(\DateTimeImmutable $date): WorkdaySummary
    {
        $workday = $this->findWorkday($date);

        $pausesMinutes = 0;
        foreach ($workday->getPauses() as $pause) {
            $pauseInterval = $pause->getStartTime()->diff($pause->getEndTime());
            $pausesMinutes += ($pauseInterval->h * 60) + $pauseInterval->i;
        }

        $endTime = $workday->getEndTime();
        $totalWorkedTimeInMinutes = null;
        $expectedEndTime = null;

        if ($endTime) {
            $workedInterval = $workday->getStartTime()->diff($endTime);
            $totalWorkedTimeInMinutes = ($workedInterval->h * 60) + $workedInterval->i - $pausesMinutes;
        } else {
            $expectedEndTime = $workday->getStartTime()
                ->modify('+8 hours')
                ->add(new \DateInterval(sprintf('PT%dM', $pausesMinutes)));
        }

        $summary = new WorkdaySummary($totalWorkedTimeInMinutes, $expectedEndTime);
        
        return $summary;
    }
}