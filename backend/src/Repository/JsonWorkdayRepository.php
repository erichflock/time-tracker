<?php

namespace App\Repository;

use App\Model\Workday;
use App\Model\Pause;
use App\Util\DateTimeInputParser;

final class JsonWorkdayRepository
{
    private string $filePath;

    public function __construct(
        private DateTimeInputParser $dateTimeInputParser,
        string $projectDir,
    ) {
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

        $key = $this->dateTimeInputParser->formatDate($workday->getDate());
        $workdays[$key] = [
            'date' => $this->dateTimeInputParser->formatDate($workday->getDate()),
            'startTime' => $this->dateTimeInputParser->formatTime($workday->getStartTime()),
            'endTime' => $workday->getEndTime()
                ? $this->dateTimeInputParser->formatTime($workday->getEndTime())
                : null,
            'pauses' => array_map(function (Pause $pause) {
                return [
                    'startTime' => $this->dateTimeInputParser->formatTime($pause->getStartTime()),
                    'endTime' => $this->dateTimeInputParser->formatTime($pause->getEndTime()),
                ];
            }, $workday->getPauses()),
        ];

        file_put_contents($this->filePath, json_encode($workdays, JSON_PRETTY_PRINT));
    }

    public function find(string $date): ?Workday
    {
        $content = file_get_contents($this->filePath);
        $workdays = json_decode($content, true) ?? [];

        $data = $workdays[$date] ?? null;

        if (!$data) {
            return null;
        }

        return $this->hydrateWorkday($data);
    }

    public function findAll(): array
    {
        $content = file_get_contents($this->filePath);
        $workdays = json_decode($content, true) ?? [];

        return array_map(
            fn (array $data) => $this->hydrateWorkday($data),
            $workdays
        );
    }

    private function hydrateWorkday(array $data): Workday
    {
        $workday = new Workday(
            $this->dateTimeInputParser->parseDate($data['date']),
            $this->dateTimeInputParser->parseTime($data['startTime'], 'start time'),
            $data['endTime']
                ? $this->dateTimeInputParser->parseTime($data['endTime'], 'end time')
                : null,
        );

        foreach ($data['pauses'] ?? [] as $pauseData) {
            $workday->addPause(new Pause(
                $this->dateTimeInputParser->parseTime($pauseData['startTime'], 'pause start time'),
                $this->dateTimeInputParser->parseTime($pauseData['endTime'], 'pause end time'),
            ));
        }

        return $workday;
    }
}
