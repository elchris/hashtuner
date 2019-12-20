<?php

namespace ChrisHolland\HashTuner\Test;

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
                new ExecutionBounds(
                    0.5,
                    1.0
                ),
                new FakeRunTime(
                    4,
                    1024000,
                    0.20
                )
            )
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();

        self::assertInstanceOf(TuningResult::class, $result);
        self::assertIsInt($result->memory);
        self::assertIsInt($result->iterations);
        self::assertIsInt($result->threads);
        self::assertIsFloat($result->executionTime);

        self::assertSame(3888638, $result->memory);
        self::assertSame(5, $result->iterations);
        self::assertSame(16, $result->threads);
        self::assertSame(0.9493745839581, $result->executionTime);
    }
}
