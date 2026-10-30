<?php

namespace App\Repository;

use App\Entity\Workday;

interface WorkdayRepositoryInterface
{
    public function save(Workday $workday): void;

    public function findByDate(string $date): ?Workday;

    public function findAll(): array;
}