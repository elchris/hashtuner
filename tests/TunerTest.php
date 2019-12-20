<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Tuner;
use PHPUnit\Framework\TestCase;

class TunerTest extends TestCase
{

    public function testDesiredLimits()
    {
        $phpProcessSize = ini_get('memory_limit');
        $this->assertSame('4096M', $phpProcessSize);

        $executionTimeOverLimit = 1.5;
        $tuner = $this->getTuner($executionTimeOverLimit);
        self::assertFalse($tuner->isSuccessFul());

        $executionTimeBelowLimit = 0.4;
        $tuner = $this->getTuner($executionTimeBelowLimit);
        self::assertFalse($tuner->isSuccessFul());
    }

    private function getTuner(float $actualExecutionTime): Tuner
    {
        $desiredExecutionTimeUpperLimit = 1.0;
        $desiredExecutionTimeLowerLimit = 0.5;

        return new Tuner(
            $desiredExecutionTimeLowerLimit,
            $desiredExecutionTimeUpperLimit,
            $actualExecutionTime
        );
    }
}
