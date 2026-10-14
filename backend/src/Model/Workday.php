<?php

namespace App\Model;

final class Workday
{
    private \DateTimeImmutable $date;
    private \DateTimeImmutable $time;

    public function __construct(
        \DateTimeImmutable $date,
        \DateTimeImmutable $time
    ) {
        $this->date = $date;
        $this->time = $time;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getTime(): \DateTimeImmutable
    {
        return $this->time;
    }
}