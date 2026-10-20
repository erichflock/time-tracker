<?php

namespace App\Util;

final class DateTimeInputParser
{
    public const DATE_FORMAT = 'd.m.Y';
    public const TIME_FORMAT = 'H:i';

    public function parseDate(?string $value, string $label = 'date'): \DateTimeImmutable
    {
        return $this->parseOrFail($value, self::DATE_FORMAT, $label);
    }

    public function parseTime(?string $value, string $label = 'time'): \DateTimeImmutable
    {
        return $this->parseOrFail($value, self::TIME_FORMAT, $label);
    }

    public function formatDate(\DateTimeImmutable $date): string
    {
        return $date->format(self::DATE_FORMAT);
    }

    public function formatTime(\DateTimeImmutable $time): string
    {
        return $time->format(self::TIME_FORMAT);
    }

    private function parseOrFail(?string $value, string $format, string $label): \DateTimeImmutable
    {
        if ($value === null || $value === '') {
            throw new \InvalidArgumentException(sprintf('Invalid %s format %s', $label, $value ?? ''));
        }

        $dateTime = \DateTimeImmutable::createFromFormat('!' . $format, $value);
        $errors = \DateTimeImmutable::getLastErrors();

        if (
            $dateTime === false
            || ($errors['warning_count'] ?? 0) > 0
            || ($errors['error_count'] ?? 0) > 0
        ) {
            throw new \InvalidArgumentException(sprintf('Invalid %s format %s', $label, $value));
        }

        return $dateTime;
    }
}
