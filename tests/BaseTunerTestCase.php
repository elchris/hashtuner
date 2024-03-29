<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Tuners\Tuner;
use PHPUnit\Framework\TestCase;

abstract class BaseTunerTestCase extends TestCase
{
    public const DEFAULT_EXECUTION_LOW = 0.5;
    public const DEFAULT_EXECUTION_HIGH = 1.0;

    protected int $threads;

    public function setUp() : void
    {
//        $info = new SystemInfo();
//        $cores = $info->getCores();
        $this->threads = 1;//$cores * 2;
    }

    /**
     * @return ExecutionBounds
     */
    protected function getExecutionBounds(): ExecutionBounds
    {
        return new ExecutionBounds(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }

    protected function assertResultCorrectness(TuningResult $result, bool $useTunerDefaults = false): void
    {
        self::assertInstanceOf(TuningResult::class, $result);
        self::assertSame(
            $result->info['options']['memory_cost'],
            $result->settingMemory
        );
        self::assertIsInt($result->settingMemory);
        self::assertIsInt($result->settingIterations);
        self::assertIsInt($result->settingThreads);
        self::assertIsFloat($result->executionTime);
        self::assertSame($this->threads, $result->settingThreads);
        if (!$useTunerDefaults) {
            self::assertSame(
                self::DEFAULT_EXECUTION_LOW,
                $result->desiredExecutionLow
            );
            self::assertSame(
                self::DEFAULT_EXECUTION_HIGH,
                $result->desiredExecutionHigh
            );
        } else {
            self::assertSame(
                Tuner::DEFAULT_EXECUTION_LOW,
                $result->desiredExecutionLow
            );
            self::assertSame(
                Tuner::DEFAULT_EXECUTION_HIGH,
                $result->desiredExecutionHigh
            );
        }
    }
}
