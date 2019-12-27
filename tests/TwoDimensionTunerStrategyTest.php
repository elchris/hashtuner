<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class TwoDimensionTunerStrategyTest extends BaseTunerTest
{
    public const UPPER = 1.0;
    public const LOWER = 0.5;
    public const INITIAL_EXEC_TIME = 0.20;

    public function testDesiredLimits(): void
    {
        $executionTimeOverLimit = self::UPPER + 0.5;
        $tuner = $this->getStrategy($executionTimeOverLimit);
        self::assertFalse($tuner->isAcceptable());

        $executionTimeBelowLimit = self::LOWER - 0.1;
        $tuner = $this->getStrategy($executionTimeBelowLimit);
        self::assertFalse($tuner->isAcceptable());

        $executionTimeWithinMemoryBump =
            (self::UPPER * TwoDimensionsTunerStrategy::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
            - 0.05
        ;
        $tuner = $this->getStrategy($executionTimeWithinMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());

        $executionTimePastMemoryBump =
            (self::UPPER * TwoDimensionsTunerStrategy::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT)
            + 0.01
        ;
        $tuner = $this->getStrategy($executionTimePastMemoryBump);
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testFirstDimensionTuningLogic(): void
    {
        $tuner = $this->getFirstDimensionTunedStrategy();
        self::assertTrue($tuner->isAcceptable());
        self::assertTrue($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testFirstDimensionTuningExceedsMemoryLimit(): void
    {
        $tuner = $this->getMemoryExceedingStrategy();
        $tuner->tuneFirstDimension();
        self::assertFalse($tuner->isAcceptable());
        self::assertFalse($tuner->hasReachedFirstDimensionBumpStopThreshold());
    }

    public function testSecondDimensionTuningLogic(): void
    {
        $tuner = $this->getFirstDimensionTunedStrategy();
        $tuner->tuneSecondDimensionBeyondAcceptability();
        self::assertFalse($tuner->isAcceptable());
        $tuner->tuneSecondDimensionBackWithinAcceptability();
        self::assertTrue($tuner->isAcceptable());
    }

    public function testOverallTuning(): void
    {
        $tuner = $this->getStrategy(self::INITIAL_EXEC_TIME);
        $initialExecTime = $tuner->getActualExecutionTime();
        self::assertFalse($tuner->isAcceptable());
        $tuner->tune();
        self::assertTrue($tuner->isAcceptable());
        $finalExecTime = $tuner->getActualExecutionTime();
        self::assertGreaterThan($initialExecTime, $finalExecTime);
    }

    public function testOverallTuningWhileExceedingMemory(): void
    {
        $tuner = $this->getMemoryExceedingStrategy();
        $tuner->tune();
        $result = $tuner->getTuningResult();
        self::assertTrue($tuner->isAcceptable());
        $this->assertResultCorrectness($result);
    }

    private function getStrategy(float $actualExecutionTime): TwoDimensionsTunerStrategy
    {
        $initialMemory = 1024000;
        return $this->getStrategyWithInitialMemory(
            $actualExecutionTime,
            $initialMemory
        );
    }

    private function getFirstDimensionTunedStrategy(): TwoDimensionsTunerStrategy
    {
        $tuner = $this->getStrategy(self::INITIAL_EXEC_TIME);
        $tuner->tuneFirstDimension();
        return $tuner;
    }

    private function getMemoryExceedingStrategy(): TwoDimensionsTunerStrategy
    {
        return $this->getStrategyWithInitialMemory(
            0.20,
            4096000
        );
    }

    /**
     * @param float $actualExecutionTime
     * @param int $initialMemory
     * @return TwoDimensionsTunerStrategy
     */
    private function getStrategyWithInitialMemory(
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
