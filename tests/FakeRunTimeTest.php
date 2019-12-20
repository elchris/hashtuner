<?php

namespace ChrisHolland\HashTuner\Test;

use PHPUnit\Framework\TestCase;

class FakeRunTimeTest extends TestCase
{
    public function testFakeRunTime()
    {
        $initialMemory = 1024000;
        $initialExecTime = 0.5;
        $loadIncrease = FakeRunTime::LOAD_INCREASE;

        $runTime = new FakeRunTime(
            $initialMemory,
            $initialExecTime
        );

        self::assertSame((float)$initialMemory, $runTime->getFirstDimension());
        self::assertSame((float)$initialExecTime, $runTime->getExecutionTime());

        $runTime->bumpFirstDimension();

        self::assertSame(
            $initialMemory + ($initialMemory * $loadIncrease),
            $runTime->getFirstDimension()
        );
        self::assertSame(
            $initialExecTime + ($initialExecTime * $loadIncrease),
            $runTime->getExecutionTime()
        );
    }
}
