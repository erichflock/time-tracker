<?php

namespace App\Util;

final class DateTimeInputParser
{
    private const DATE_FORMAT = 'd.m.Y';
    private const TIME_FORMAT = 'H:i';

    public static function parseDate(?string $value, string $label = 'date'): \DateTimeImmutable
    {
        return self::parseOrFail($value, self::DATE_FORMAT, $label);
    }

    public static function parseTime(?string $value, string $label = 'time'): \DateTimeImmutable
    {
        return self::parseOrFail($value, self::TIME_FORMAT, $label);
    }

    private static function parseOrFail(?string $value, string $format, string $label): \DateTimeImmutable
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
