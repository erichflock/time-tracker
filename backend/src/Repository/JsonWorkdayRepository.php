<?php

namespace App\Repository;

use App\Model\Workday;
use App\Model\Pause;

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
            'startTime' => $workday->getStartTime()->format('H:i'),
            'endTime' => $workday->getEndTime() ? $workday->getEndTime()->format('H:i') : null,
            'pauses' => array_map(function (Pause $pause) {
                return [
                    'startTime' => $pause->getStartTime()->format('H:i'),
                    'endTime' => $pause->getEndTime()->format('H:i'),
                ];
            }, $workday->getPauses()),
        ];

        file_put_contents($this->filePath, json_encode($workdays, JSON_PRETTY_PRINT));
    }

    public function find(string $date): ?Workday
    {
        $content = file_get_contents($this->filePath);
        $workdays = json_decode($content, true) ?? [];

        $key = $date;
        $data = $workdays[$key] ?? null;

        if (!$data) {
            return null;
        }

        $endTime = $data['endTime']
            ? \DateTimeImmutable::createFromFormat('H:i', $data['endTime'])
            : null;

        $workday = new Workday(
            \DateTimeImmutable::createFromFormat('d.m.Y', $data['date']),
            \DateTimeImmutable::createFromFormat('H:i', $data['startTime']),
            $endTime ?: null
        );

        foreach ($data['pauses'] ?? [] as $pauseData) {
            $workday->addPause(new Pause(
                \DateTimeImmutable::createFromFormat('H:i', $pauseData['startTime']),
                \DateTimeImmutable::createFromFormat('H:i', $pauseData['endTime']),
            ));
        }

        return $workday;
    }

    public function findAll(): array
    {
        $content = file_get_contents($this->filePath);
        $workdays = json_decode($content, true) ?? [];
        return array_map(function (array $data) {
            return new Workday(
                \DateTimeImmutable::createFromFormat('d.m.Y', $data['date']),
                \DateTimeImmutable::createFromFormat('H:i', $data['startTime']),
                $data['endTime'] ? \DateTimeImmutable::createFromFormat('H:i', $data['endTime']) : null,
                array_map(function (array $pauseData) {
                    return new Pause(
                        \DateTimeImmutable::createFromFormat('H:i', $pauseData['startTime']),
                        \DateTimeImmutable::createFromFormat('H:i', $pauseData['endTime']),
                    );
                }, $data['pauses'] ?? [])
            );
        }, $workdays);
    }
}
