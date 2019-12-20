<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\ExecutionBounds;
use ChrisHolland\HashTuner\TunerStrategy;
use ChrisHolland\HashTuner\TwoDimensionsTunerStrategy;
use PHPUnit\Framework\TestCase;

class TwoDimensionTunerStrategyTest extends TestCase
{

    const UPPER = 1.0;
    const LOWER = 0.5;
    const INITIAL_EXEC_TIME = 0.20;

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
            (self::UPPER * TwoDimensionsTunerStrategy::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
            - 0.05
        ;
        $tuner = $this->getTuner($executionTimeWithinMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());

        $executionTimePastMemoryBump =
            (self::UPPER * TwoDimensionsTunerStrategy::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
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

    public function testFirstDimensionTuningExceedsMemoryLimit()
    {
        $tuner = $this->getMemoryExceedingTuner();
        $tuner->tuneFirstDimension();
        echo "\n ********* Mem Exceeding Status:".$tuner->getRunTimeInfo();
        self::assertFalse($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());
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

    public function testOverallTuning()
    {
        $tuner = $this->getTuner(self::INITIAL_EXEC_TIME);
        $initialExecTime = $tuner->getActualExecutionTime();
        echo "\n*** Global Tune Start: " . $tuner->getRunTimeInfo();
        self::assertFalse($tuner->isAcceptable());
        $tuner->tune();
        self::assertTrue($tuner->isAcceptable());
        echo "\n*** Global Tune End: " . $tuner->getRunTimeInfo();
        $finalExecTime = $tuner->getActualExecutionTime();
        self::assertGreaterThan($initialExecTime, $finalExecTime);
    }

    public function testOverallTuningWhileExceedingMemory()
    {
        $tuner = $this->getMemoryExceedingTuner();
        $tuner->tune();
        $result = $tuner->getTuningResult();
        self::assertTrue($tuner->isAcceptable());
        echo "\n***** Memory Exceeding Fake Tuner Result:";
        var_dump($result);
    }

    private function getTuner(float $actualExecutionTime): TunerStrategy
    {
        $initialMemory = 1024000;
        return $this->getTunerWithInitialMemory(
            $actualExecutionTime,
            $initialMemory
        );
    }

    /**
     * @return TunerStrategy
     */
    private function getFirstDimensionTunedTuner(): TunerStrategy
    {
        $tuner = $this->getTuner(self::INITIAL_EXEC_TIME);
        echo "\n*** RunTime State Start: " . $tuner->getRunTimeInfo();
        $tuner->tuneFirstDimension();
        echo "\n*** RunTime State End: " . $tuner->getRunTimeInfo();

        return $tuner;
    }

    private function getMemoryExceedingTuner()
    {
        return $this->getTunerWithInitialMemory(
            0.20,
            4096000
        );
    }

    /**
     * @param float $actualExecutionTime
     * @param int $initialMemory
     * @return TwoDimensionsTunerStrategy
     */
    private function getTunerWithInitialMemory(
        float $actualExecutionTime,
        int $initialMemory
    ): TwoDimensionsTunerStrategy {
        return new TwoDimensionsTunerStrategy(
            new ExecutionBounds(
                self::LOWER,
                self::UPPER
            ),
            new FakeRunTime(
                4,
                $initialMemory,
                $actualExecutionTime
            )
        );
    }
}
