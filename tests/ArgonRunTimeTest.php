<?php

namespace ChrisHolland\HashTuner\Test;

use BrandEmbassy\Memory\MemoryLimitNotSetException;
use ChrisHolland\HashTuner\ArgonRunTime;
use ChrisHolland\HashTuner\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\MemoryInfo;
use PHPUnit\Framework\TestCase;

class ArgonRunTimeTest extends TestCase
{
    /**
     * @throws MemoryLimitNotSetException
     * @throws FirstDimensionLimitViolation
     */
    public function testMemoryHardLimitViolation()
    {
        $limitInKiloBytes = (new MemoryInfo())->getMemoryLimitInKiloBytes();

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
}
