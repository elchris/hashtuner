<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\ArgonRunTime;
use ChrisHolland\HashTuner\ExecutionBounds;
use ChrisHolland\HashTuner\Tuner;
use ChrisHolland\HashTuner\TuningResult;
use ChrisHolland\HashTuner\TwoDimensionsTunerStrategy;
use PHPUnit\Framework\TestCase;

class TunerTest extends TestCase
{
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
        self::assertSame(16, $result->threads);
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
        self::assertSame(16, $result->threads);

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
