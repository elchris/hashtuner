<?php

namespace ChrisHolland\HashTuner\Test;

use PHPUnit\Framework\TestCase;

class FakeRunTimeTest extends TestCase
{
    const DEFAULT_ITERATIONS = 4;
    const DEFAULT_MEMORY = 1024000;
    const DEFAULT_EXEC_TIME = 0.5;

    public function testFakeRunTime()
    {
        $initialIterations = self::DEFAULT_ITERATIONS;
        $initialMemory = self::DEFAULT_MEMORY;
        $initialExecTime = self::DEFAULT_EXEC_TIME;
        $loadIncrease = FakeRunTime::LOAD_INCREASE;

        $runTime = $this->getFakeRunTime();

        self::assertSame((float)$initialMemory, $runTime->getFirstDimension());
        self::assertSame((float)$initialExecTime, $runTime->getExecutionTime());
        self::assertSame((int) $initialIterations, $runTime->getSecondDimension());

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

//    public function testMemoryHardLimitViolation()
//    {
//        $runTime = $this->getFakeRunTime();
//
//        $this->expectException(\ChrisHolland\HashTuner\MemoryLimitViolation::class);
//
//        $attemptedLimit = 4096000;
//        while ($runTime->getFirstDimension() < $attemptedLimit) {
//            $runTime->bumpFirstDimension();
//        }
//    }

    private function getFakeRunTime(): FakeRunTime
    {
        return new FakeRunTime(
            self::DEFAULT_ITERATIONS,
            self::DEFAULT_MEMORY,
            self::DEFAULT_EXEC_TIME
        );
    }
}
