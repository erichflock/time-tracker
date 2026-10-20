<?php

namespace App\Tests\Util;

use App\Util\TimeValidator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class TimeValidatorTest extends TestCase
{
    public function testWhenEndTimeIsBeforeStartTimeShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startTime = new \DateTimeImmutable('09:00:00');
        $endTime = new \DateTimeImmutable('08:00:00');
        $this->expectException(InvalidArgumentException::class);
        $timeValidator->assertReasonableWorkdayEndTime($startTime, $endTime);
    }

    public function testWhenEndTimeIsEqualToStartTimeShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startTime = new \DateTimeImmutable('09:00:00');
        $endTime = new \DateTimeImmutable('09:00:00');
        $this->expectException(InvalidArgumentException::class);
        $timeValidator->assertReasonableWorkdayEndTime($startTime, $endTime);
    }

    public function testWhenEndTimeIsAfterStartTimeShouldPass(): void
    {
        $timeValidator = new TimeValidator();
        $startTime = new \DateTimeImmutable('09:00:00');
        $endTime = new \DateTimeImmutable('10:00:00');
        $timeValidator->assertReasonableWorkdayEndTime($startTime, $endTime);
        $this->assertTrue(true);

        $startTime = new \DateTimeImmutable('09:00:00');
        $endTime = new \DateTimeImmutable('09:01:00');
        $timeValidator->assertReasonableWorkdayEndTime($startTime, $endTime);
        $this->assertTrue(true);
    }
}