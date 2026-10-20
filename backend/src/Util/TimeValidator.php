<?php

namespace App\Util;

final class TimeValidator
{
    public function assertReasonableWorkdayEndTime(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): void
    {
        print_r("StartTime: " . $startTime->format('H:i:s') . "\n");
        print_r("EndTime: " . $endTime->format('H:i:s') . "\n");
        if ($endTime < $startTime) {
            throw new \InvalidArgumentException('End time is before start time');
        }

        if ($endTime == $startTime) {
            throw new \InvalidArgumentException('End time is equal to start time');
        }
    }
}