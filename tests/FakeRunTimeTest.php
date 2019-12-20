<?php

namespace ChrisHolland\HashTuner\Test;

use PHPUnit\Framework\TestCase;

class FakeRunTimeTest extends TestCase
{
    public function testFakeRunTime()
    {
        $initialIterations = 4;
        $initialMemory = 1024000;
        $initialExecTime = 0.5;
        $loadIncrease = FakeRunTime::LOAD_INCREASE;

        $runTime = new FakeRunTime(
            $initialIterations,
            $initialMemory,
            $initialExecTime
        );

        self::assertSame((float)$initialMemory, $runTime->getFirstDimension());
        self::assertSame((float)$initialExecTime, $runTime->getExecutionTime());

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
    }
}
