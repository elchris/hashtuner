<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Tuners\Tuner;
use PHPUnit\Framework\TestCase;

abstract class BaseTunerTest extends TestCase
{
    public const DEFAULT_EXECUTION_LOW = 0.5;
    public const DEFAULT_EXECUTION_HIGH = 1.0;

    /**
     * @var int
     */
    protected $threads;

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
        self::assertIsInt($result->memory);
        self::assertIsInt($result->iterations);
        self::assertIsInt($result->threads);
        self::assertIsFloat($result->executionTime);
        self::assertSame($this->threads, $result->threads);
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
