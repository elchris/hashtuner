<?php

namespace ChrisHolland\HashTuner\Test;

use BrandEmbassy\Memory\MemoryLimitNotSetException;
use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\SystemInfo;
use PHPUnit\Framework\TestCase;

class ArgonRunTimeTest extends TestCase
{
    /**
     * @throws MemoryLimitNotSetException
     * @throws FirstDimensionLimitViolation
     */
    public function testMemoryHardLimitViolation()
    {
        $limitInKiloBytes = (new SystemInfo())->getMemoryLimitInKiloBytes();

        $attemptedLimit = $limitInKiloBytes * 1.1;

        $runTime = new ArgonRunTime(
            intval($attemptedLimit / 1.2),
            3
        );

        $this->expectException(FirstDimensionLimitViolation::class);

        while ($runTime->getFirstDimension() < $attemptedLimit) {
            $runTime->bumpFirstDimension();
        }
        echo
            "***** ArgonRunTimeTest::testMemoryHardLimitViolation Memory: "
            .$runTime->getFirstDimension();
        echo
            "***** ArgonRunTimeTest::testMemoryHardLimitViolation Execution: "
            .$runTime->getExecutionTime();
    }

    public function testMemoryLimitOverride()
    {
        $runTime = new ArgonRunTime(
            128000,
            3,
            256000
        );

        self::assertSame(256000, $runTime->getHardMemoryLimitInKilobytes());
    }
}
