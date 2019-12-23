<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;
use ChrisHolland\HashTuner\SystemInfo;
use ChrisHolland\HashTuner\Tuner;
use PHPUnit\Framework\TestCase;

class TunerTest extends TestCase
{
    const DEFAULT_EXECUTION_LOW = 0.5;
    const DEFAULT_EXECUTION_HIGH = 1.0;
    /**
     * @var int
     */
    private $threads;

    public function setUp() : void
    {
        $info = new SystemInfo();
        $cores = $info->getCores();
        $this->threads = $cores * 2;
    }
    public function testTunerWithFakeRunTime()
    {
        $tuner = new Tuner(
            new TwoDimensionsTunerStrategy(
                $this->getExecutionBounds(),
                new FakeRunTime(
                    4,
                    1024000,
                    0.20
                )
            )
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();

        $this->assertResultCorrectness($result, false);

        self::assertSame(3888638, $result->memory);
        self::assertSame(5, $result->iterations);
        self::assertSame($this->threads, $result->threads);
        self::assertSame(0.9493745839581, $result->executionTime);
    }

    public function testTunerWithArgonRunTime()
    {
        $tuner = new Tuner(
            $this->getTwoDimensionsTunerStrategy()
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();
        $this->assertResultCorrectness($result, false);
        self::assertSame($this->threads, $result->threads);
        var_dump($result);
    }

    public function testArgonTunerResultsWithDefaults()
    {
        $result = Tuner::getTunedArgonSettings();
        $this->assertResultCorrectness($result);
        var_dump($result);
    }

    public function testArgonTunerWithDefaults()
    {
        $tuner = Tuner::getArgonTuner();
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    public function testArgonTunerWithCustomSpeedAndMemoryLimit()
    {
        $results = Tuner::getTunedArgonSettingsForSpeedAndMemoryLimit(
            0.5,
            1.0,
            256000
        );
        var_dump($results);
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomMemoryLimit()
    {
        $results = Tuner::getTunedArgonSettingsForMemoryLimit(
            256000
        );
        var_dump($results);
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomStrategy()
    {
        $tuner = Tuner::getArgonTunerWithStrategy(
            $this->getTwoDimensionsTunerStrategy()
        );
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    /**
     * @return ExecutionBounds
     */
    private function getExecutionBounds(): ExecutionBounds
    {
        return new ExecutionBounds(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }

    private function assertResultCorrectness(TuningResult $result, bool $useTunerDefaults = false): void
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

    /**
     * @return TwoDimensionsTunerStrategy
     */
    private function getTwoDimensionsTunerStrategy(): TwoDimensionsTunerStrategy
    {
        return new TwoDimensionsTunerStrategy(
            $this->getExecutionBounds(),
            new ArgonRunTime(
                512000,
                3
            )
        );
    }
}
