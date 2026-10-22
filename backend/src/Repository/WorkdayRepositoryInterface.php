<?php

namespace App\Repository;

use App\Model\Workday;

interface WorkdayRepositoryInterface
{
    public function save(Workday $workday): void;

    public function find(string $date): ?Workday;

    public function findAll(): array;
}