<?php

namespace App\Dto;

final class WorkdaySummary
{
    public function __construct(
        public ?int $totalWorkedTimeInMinutes,
        public ?\DateTimeImmutable $expectedEndTime,
    ) {}
}