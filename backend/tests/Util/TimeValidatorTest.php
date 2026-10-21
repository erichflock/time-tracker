<?php

namespace App\Tests\Util;

use App\Util\TimeValidator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use App\Model\Workday;
use App\Model\Pause;

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

    public function testWhenEndPauseIsBeforeStartPauseShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('12:00:00');
        $endPause = new \DateTimeImmutable('11:00:00');
        $this->expectException(InvalidArgumentException::class);
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    public function testWhenEndPauseIsEqualToStartPauseShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('12:00:00');
        $endPause = new \DateTimeImmutable('12:00:00');
        $this->expectException(InvalidArgumentException::class);
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    public function testWhenStartPauseIsBeforeStartWorkdayShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('08:59:00');
        $endPause = new \DateTimeImmutable('10:00:00');
        $this->expectException(InvalidArgumentException::class);
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    public function testWhenStartPauseIsAfterEndWorkdayShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('18:01:00');
        $endPause = new \DateTimeImmutable('19:00:00');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('Start pause is after end workday');
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    public function testWhenEndPauseIsAfterEndWorkdayShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('17:01:00');
        $endPause = new \DateTimeImmutable('18:01:00');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('End pause is after end workday');
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    public function testWhenPauseIntersectsWithAnotherPauseShouldFail(): void
    {
        $timeValidator = new TimeValidator();
        $startPause = new \DateTimeImmutable('12:00:00');
        $endPause = new \DateTimeImmutable('13:00:00');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('Pause intersects with another pause');
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());

        $startPause = new \DateTimeImmutable('11:30:00');
        $endPause = new \DateTimeImmutable('12:30:00');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('Pause intersects with another pause');
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());

        $startPause = new \DateTimeImmutable('12:30:00');
        $endPause = new \DateTimeImmutable('13:30:00');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('Pause intersects with another pause');
        $timeValidator->assertReasonableWorkdayPause($startPause, $endPause, $this->provideWorkday());
    }

    /**
     * @dataProvider provideWorkday
     */
    private function provideWorkday(): Workday
    {
        $date = new \DateTimeImmutable('2026-01-01');
        $startTime = new \DateTimeImmutable('09:00:00');
        $endTime = new \DateTimeImmutable('18:00:00');
        $workday = new Workday($date, $startTime, $endTime);
        $startPause = new \DateTimeImmutable('12:00:00');
        $endPause = new \DateTimeImmutable('13:00:00');
        $pause = new Pause($startPause, $endPause);
        $workday->addPause($pause);
        
        return $workday;
    }
}