<?php

namespace App\Repository;

use App\Model\Workday;

final class JsonWorkdayRepository
{
    private string $filePath;

    public function __construct(string $projectDir)
    {
        $this->filePath = $projectDir . '/var/data/workdays.json';
    }

    public function save(Workday $workday): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $content = file_exists($this->filePath)
            ? file_get_contents($this->filePath)
            : '{}';

        $workdays = json_decode($content, true) ?? [];

        $key = $workday->getDate()->format('d.m.Y');
        $workdays[$key] = [
            'date' => $workday->getDate()->format('d.m.Y'),
            'time' => $workday->getTime()->format('H:i'),
        ];

        file_put_contents($this->filePath, json_encode($workdays, JSON_PRETTY_PRINT));
    }
}
