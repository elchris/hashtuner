<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use PHPUnit\Framework\TestCase;

class FakeRunTimeTest extends TestCase
{
    public const DEFAULT_ITERATIONS = 4;
    public const DEFAULT_MEMORY = 1024000;
    public const DEFAULT_EXEC_TIME = 0.5;

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function testFakeRunTime(): void
    {
        $initialIterations = self::DEFAULT_ITERATIONS;
        $initialMemory = self::DEFAULT_MEMORY;
        $initialExecTime = self::DEFAULT_EXEC_TIME;
        $loadIncrease = FakeRunTime::LOAD_INCREASE;

        $runTime = $this->getFakeRunTime();

        self::assertSame((float)$initialMemory, $runTime->getFirstDimension());
        self::assertSame($initialExecTime, $runTime->getExecutionTime());
        self::assertSame($initialIterations, $runTime->getSecondDimension());

        $runTime->bumpFirstDimension();

        $increasedMemory = $initialMemory + ($initialMemory * $loadIncrease);
        self::assertSame(
            $increasedMemory,
            $runTime->getFirstDimension()
        );
        $increasedExecutionTime = $initialExecTime + ($initialExecTime * $loadIncrease);
        self::assertSame(
            $increasedExecutionTime,
            $runTime->getExecutionTime()
        );

        $runTime->bumpSecondDimension();

        self::assertSame($initialIterations + 1, $runTime->getSecondDimension());
        self::assertSame($increasedExecutionTime * FakeRunTime::LOAD_MULTIPLIER, $runTime->getExecutionTime());
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function testMemoryHardLimitViolation(): void
    {
        $runTime = $this->getFakeRunTime();

        $this->expectException(FirstDimensionLimitViolation::class);

        $attemptedLimit = 8192000;
        while ($runTime->getFirstDimension() < $attemptedLimit) {
            $runTime->bumpFirstDimension();
        }
    }

    private function getFakeRunTime(): FakeRunTime
    {
        return new FakeRunTime(
            self::DEFAULT_ITERATIONS,
            self::DEFAULT_MEMORY,
            self::DEFAULT_EXEC_TIME
        );
    }
}
