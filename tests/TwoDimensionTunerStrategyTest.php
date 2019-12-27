<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\Strategy\TunerStrategy;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class TwoDimensionTunerStrategyTest extends BaseTunerTest
{
    public const UPPER = 1.0;
    public const LOWER = 0.5;
    public const INITIAL_EXEC_TIME = 0.20;

    public function testDesiredLimits(): void
    {
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

    public function testFirstDimensionTuningLogic(): void
    {
        $tuner = $this->getFirstDimensionTunedTuner();
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testFirstDimensionTuningExceedsMemoryLimit(): void
    {
        $tuner = $this->getMemoryExceedingTuner();
        $tuner->tuneFirstDimension();
        self::assertFalse($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testSecondDimensionTuningLogic(): void
    {
        $tuner = $this->getFirstDimensionTunedTuner();
        $tuner->tuneSecondDimensionBeyondAcceptability();
        self::assertFalse($tuner->isAcceptable());
        $tuner->tuneSecondDimensionBackWithinAcceptability();
        self::assertTrue($tuner->isAcceptable());
    }

    public function testOverallTuning(): void
    {
        $tuner = $this->getTuner(self::INITIAL_EXEC_TIME);
        $initialExecTime = $tuner->getActualExecutionTime();
        self::assertFalse($tuner->isAcceptable());
        $tuner->tune();
        self::assertTrue($tuner->isAcceptable());
        $finalExecTime = $tuner->getActualExecutionTime();
        self::assertGreaterThan($initialExecTime, $finalExecTime);
    }

    public function testOverallTuningWhileExceedingMemory(): void
    {
        $tuner = $this->getMemoryExceedingTuner();
        $tuner->tune();
        $result = $tuner->getTuningResult();
        self::assertTrue($tuner->isAcceptable());
        $this->assertResultCorrectness($result);
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
        $tuner->tuneFirstDimension();
        return $tuner;
    }

    private function getMemoryExceedingTuner(): TwoDimensionsTunerStrategy
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
