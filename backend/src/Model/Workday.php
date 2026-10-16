<?php

namespace App\Model;

use App\Model\Pause;

final class Workday
{
    private \DateTimeImmutable $date;
    private \DateTimeImmutable $startTime;
    private ?\DateTimeImmutable $endTime;
    private array $pauses = [];

    public function __construct(
        \DateTimeImmutable $date,
        \DateTimeImmutable $startTime,
        ?\DateTimeImmutable $endTime
    ) {
        $this->date = $date;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getPauses(): array
    {
        return $this->pauses;
    }

    public function addPause(Pause $pause): void
    {
        $this->pauses[] = $pause;
    }
}