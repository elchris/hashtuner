<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\RunTime\HashRunTime;
use ChrisHolland\HashTuner\SystemInfo;
use PHPUnit\Framework\TestCase;

class ArgonRunTimeTest extends TestCase
{
    public function testGetRunTimeInfo(): void
    {
        $runTime = $this->getArgonRunTime();
        $info = $runTime->getInfo();
        self::assertIsArray($info);
        self::assertArrayHasKey('algoName', $info);
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function testMemoryHardLimitViolation(): void
    {
        (new SystemInfo())->getMemoryLimitInKiloBytes();
        $limitInKiloBytes = 32000;
        $attemptedLimit = $limitInKiloBytes * 1.1;

        $runTime = new ArgonRunTime(
            (int)($attemptedLimit / 1.2),
            3,
            $limitInKiloBytes
        );

        $this->expectException(FirstDimensionLimitViolation::class);

        while ($runTime->getFirstDimension() < $attemptedLimit) {
            $runTime->bumpFirstDimension();
        }

        self::assertGreaterThanOrEqual($attemptedLimit, $runTime->getFirstDimension());
    }

    public function testMemoryLimitOverride(): void
    {
        $runTime = $this->getArgonRunTime();

        self::assertSame(256000, $runTime->getHardMemoryLimitInKilobytes());
    }

    private function getArgonRunTime(): HashRunTime
    {
        return new ArgonRunTime(
            128000,
            3,
            256000
        );
    }
}
