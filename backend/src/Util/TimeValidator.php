<?php

namespace App\Util;

use App\Model\Workday;
use App\Model\Pause;

final class TimeValidator
{
    public function assertReasonableWorkdayEndTime(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): void
    {
        if ($endTime < $startTime) {
            throw new \InvalidArgumentException('End time is before start time');
        }

        if ($endTime == $startTime) {
            throw new \InvalidArgumentException('End time is equal to start time');
        }
    }

    public function assertReasonableWorkdayPause(\DateTimeImmutable $startPause, \DateTimeImmutable $endPause, Workday $workday): void
    {
        if ($endPause < $startPause) {
            throw new \InvalidArgumentException('End pause is before start pause');
        }

        if ($endPause == $startPause) {
            throw new \InvalidArgumentException('End pause is equal to start pause');
        }

        if ($startPause < $workday->getStartTime()) {
            throw new \InvalidArgumentException('Start pause is before start workday');
        }

        if ($startPause > $workday->getEndTime()) {
            throw new \InvalidArgumentException('Start pause is after end workday');
        }

        if ($endPause > $workday->getEndTime()) {
            throw new \InvalidArgumentException('End pause is after end workday');
        }

        $pause = new Pause($startPause, $endPause);
        foreach ($workday->getPauses() as $existingPause) {
            if ($this->doPausesIntersect($existingPause, $pause)) {
                throw new \InvalidArgumentException('Pause intersects with another pause');
            }
        }
    }

    private function doPausesIntersect(Pause $pause1, Pause $pause2): bool
    {
        return $pause1->getStartTime() < $pause2->getEndTime() && $pause1->getEndTime() > $pause2->getStartTime();
    }
}