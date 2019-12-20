<?php

namespace ChrisHolland\HashTuner\Test;

use PHPUnit\Framework\TestCase;

class FakeRunTimeTest extends TestCase
{
    public function testFakeRunTime()
    {
        $initialMemory = 1024000;
        $initialExecTime = 0.5;
        $runTime = new FakeRunTime(
            $initialMemory,
            $initialExecTime
        );

        self::assertSame((float)$initialMemory, $runTime->getMemory());
        self::assertSame((float)$initialExecTime, $runTime->getExecutionTime());
        $runTime->bumpMemory();
        $loadIncrease = FakeRunTime::LOAD_INCREASE;
        self::assertSame(
            $initialMemory + ($initialMemory * $loadIncrease),
            $runTime->getMemory()
        );
        self::assertSame(
            $initialExecTime + ($initialExecTime * $loadIncrease),
            $runTime->getExecutionTime()
        );
    }
}
