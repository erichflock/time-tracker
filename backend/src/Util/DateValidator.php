<?php

namespace App\Util;

final class DateValidator
{   
    private ?int $currentYear;

    public function __construct(?int $currentYear = null)
    {
        $this->currentYear = $currentYear ?? date('Y');
    }

    public function assertReasonableWorkdayDate(\DateTimeImmutable $date): void
    {
        $year = $date->format('Y');
        
        if ($year < $this->currentYear) {
            throw new \InvalidArgumentException('Date is before ' . $this->currentYear);
        }

        if ($year > $this->currentYear) {
            throw new \InvalidArgumentException('Date is after ' . $this->currentYear + 1);
        }
    }
}
