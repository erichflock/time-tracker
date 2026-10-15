<?php

namespace App\Model;

final class Workday
{
    private \DateTimeImmutable $date;
    private \DateTimeImmutable $startTime;

    public function __construct(
        \DateTimeImmutable $date,
        \DateTimeImmutable $startTime
    ) {
        $this->date = $date;
        $this->startTime = $startTime;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }
}