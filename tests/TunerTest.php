<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Tuner;
use PHPUnit\Framework\TestCase;

class TunerTest extends TestCase
{

    const UPPER = 1.0;
    const LOWER = 0.5;

    public function testDesiredLimits()
    {
        $phpProcessSize = ini_get('memory_limit');
        $this->assertSame('4096M', $phpProcessSize);

        $executionTimeOverLimit = self::UPPER + 0.5;
        $tuner = $this->getTuner($executionTimeOverLimit);
        self::assertFalse($tuner->isAcceptable());

        $executionTimeBelowLimit = self::LOWER - 0.1;
        $tuner = $this->getTuner($executionTimeBelowLimit);
        self::assertFalse($tuner->isAcceptable());

        $executionTimeWithinMemoryBump =
            (self::UPPER * Tuner::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
            - 0.05
        ;
        $tuner = $this->getTuner($executionTimeWithinMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());

        $executionTimePastMemoryBump =
            (self::UPPER * Tuner::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
            + 0.01
        ;
        $tuner = $this->getTuner($executionTimePastMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testFirstDimensionTuningLogic()
    {
        $tuner = $this->getFirstDimensionTunedTuner();
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testSecondDimensionTuningLogic()
    {
        $tuner = $this->getFirstDimensionTunedTuner();
        echo "\n*** RunTime State Start: " . $tuner->getRunTimeInfo();
        $tuner->tuneSecondDimensionBeyondAcceptability();
        self::assertFalse($tuner->isAcceptable());
        echo "\n*** RunTime State Middle: " . $tuner->getRunTimeInfo();
        $tuner->tuneSecondDimensionBackWithinAcceptability();
        echo "\n*** RunTime State End: " . $tuner->getRunTimeInfo();
        self::assertTrue($tuner->isAcceptable());
    }

    private function getTuner(float $actualExecutionTime): Tuner
    {
        $desiredExecutionTimeUpperLimit = self::UPPER;
        $desiredExecutionTimeLowerLimit = self::LOWER;

        return new Tuner(
            $desiredExecutionTimeLowerLimit,
            $desiredExecutionTimeUpperLimit,
            new FakeRunTime(
                4,
                1024000,
                $actualExecutionTime
            )
        );
    }

    /**
     * @return Tuner
     */
    private function getFirstDimensionTunedTuner(): Tuner
    {
        $tuner = $this->getTuner(0.20);
        echo "\n*** RunTime State Start: " . $tuner->getRunTimeInfo();
        $tuner->tuneFirstDimension();
        echo "\n*** RunTime State End: " . $tuner->getRunTimeInfo();

        return $tuner;
    }
}
