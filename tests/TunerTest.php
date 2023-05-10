<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;
use ChrisHolland\HashTuner\Tuners\Tuner;

class TunerTest extends BaseTunerTest
{
    public function testTunerWithFakeRunTime(): void
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

        self::assertSame(3888638, $result->settingMemory);
        self::assertSame(5, $result->settingIterations);
        self::assertSame($this->threads, $result->settingThreads);
        self::assertSame(0.9493745839581031, $result->executionTime);
    }
}
