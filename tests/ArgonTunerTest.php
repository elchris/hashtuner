<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;
use ChrisHolland\HashTuner\Tuners\ArgonTuner;
use ChrisHolland\HashTuner\Tuners\Tuner;

class ArgonTunerTest extends BaseTunerTest
{
    public function testTunerWithArgonRunTime(): void
    {
        $tuner = new Tuner(
            $this->getArgonTwoDimensionsTunerStrategy()
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();
        $this->assertResultCorrectness($result, false);
        self::assertSame($this->threads, $result->threads);
    }

    public function testArgonTunerResultsWithDefaults(): void
    {
        $result = ArgonTuner::getTunedArgonSettings();
        $this->assertResultCorrectness($result);
    }

    public function testArgonTunerWithDefaults(): void
    {
        $tuner = ArgonTuner::getArgonTuner();
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    public function testArgonTunerWithCustomSpeedAndMemoryLimit(): void
    {
        $results = ArgonTuner::getTunedArgonSettingsForSpeedAndMemoryLimit(
            0.5,
            1.0,
            256000
        );
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomMemoryLimit(): void
    {
        $results = ArgonTuner::getTunedArgonSettingsForMemoryLimit(
            256000
        );
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomStrategy(): void
    {
        $tuner = Tuner::getTunerWithStrategy(
            $this->getArgonTwoDimensionsTunerStrategy()
        );
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    private function getArgonTwoDimensionsTunerStrategy(): TwoDimensionsTunerStrategy
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
