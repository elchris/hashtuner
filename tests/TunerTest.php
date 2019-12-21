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

        $this->assertResultCorrectness($result);

        self::assertSame(3888638, $result->memory);
        self::assertSame(5, $result->iterations);
        self::assertSame($this->threads, $result->threads);
        self::assertSame(0.9493745839581, $result->executionTime);
    }

    public function testTunerWithArgonRunTime()
    {
        $tuner = new Tuner(
            new TwoDimensionsTunerStrategy(
                $this->getExecutionBounds(),
                new ArgonRunTime(
                    512000,
                    3
                )
            )
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();
        $this->assertResultCorrectness($result);
        self::assertSame($this->threads, $result->threads);
        var_dump($result);
    }

    public function testArgonTunerWithDefaults()
    {
        $result = Tuner::getTunedArgonSettings();
        $this->assertResultCorrectness($result);
        self::assertSame($this->threads, $result->threads);
        var_dump($result);
    }

    /**
     * @return ExecutionBounds
     */
    private function getExecutionBounds(): ExecutionBounds
    {
        return new ExecutionBounds(
            0.5,
            1.0
        );
    }

    /**
     * @param TuningResult $result
     */
    private function assertResultCorrectness(TuningResult $result): void
    {
        self::assertInstanceOf(TuningResult::class, $result);
        self::assertIsInt($result->memory);
        self::assertIsInt($result->iterations);
        self::assertIsInt($result->threads);
        self::assertIsFloat($result->executionTime);
    }
}
