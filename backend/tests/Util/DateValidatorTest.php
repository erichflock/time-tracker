<?php

namespace App\Tests\Util;

use App\Util\DateValidator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class DateValidatorTest extends TestCase
{
    public function testWhenDateBeforeCurrentYearShouldFail(): void {
        $dateValidator = new DateValidator(2026);
        $date = new \DateTimeImmutable('2025-12-31');
        $this->expectException(InvalidArgumentException::class);
        $dateValidator->assertReasonableWorkdayDate($date);

        $dateValidator = new DateValidator(2030);
        $date = new \DateTimeImmutable('2029-12-31');
        $this->expectException(InvalidArgumentException::class);
        $dateValidator->assertReasonableWorkdayDate($date);
    }

    public function testWhenDateInCurrentYearShouldPass(): void {
        $dateValidator = new DateValidator(2026);
        $date = new \DateTimeImmutable('2026-01-01');
        $dateValidator->assertReasonableWorkdayDate($date);
        $this->assertTrue(true);
    }

    public function testWhenDateInNextYearShouldFail(): void {
        $dateValidator = new DateValidator(2026);
        $date = new \DateTimeImmutable('2027-01-01');
        $this->expectException(InvalidArgumentException::class);
        $dateValidator->assertReasonableWorkdayDate($date);
    }
}