<?php

namespace App\Model;

final class Pause
{
    private \DateTimeImmutable $startTime;
    private \DateTimeImmutable $endTime;

    public function __construct(
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime
    ) {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
    
    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return $this->endTime;
    }
}