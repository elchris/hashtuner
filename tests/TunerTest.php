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
        self::assertFalse($tuner->isAcceptable());

        $executionTimeBelowLimit = 0.4;
        $tuner = $this->getTuner($executionTimeBelowLimit);
        self::assertFalse($tuner->isAcceptable());

        $executionTimeWithinMemoryBump = 0.85;
        $tuner = $this->getTuner($executionTimeWithinMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedMemoryBumpStopThreshold());

        $executionTimePastMemoryBump = 0.91;
        $tuner = $this->getTuner($executionTimePastMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedMemoryBumpStopThreshold());
    }

    //public function testConditions()
    //{
        //while
            //tuner->hasNotPassedLowerThreshold
            //OR
            //
            //tuner->isAcceptable
            //AND
            //NOT tuner->hasReachedMemoryBumpStopThreshold
            //
            //THEN
                //Bump up the memory, slower run time
    //}

    private function getTuner(float $actualExecutionTime): Tuner
    {
        $desiredExecutionTimeUpperLimit = 1.0;
        $desiredExecutionTimeLowerLimit = 0.5;

        return new Tuner(
            $desiredExecutionTimeLowerLimit,
            $desiredExecutionTimeUpperLimit,
            new FakeRunTime(
                1024000,
                $actualExecutionTime
            )
        );
    }
}
