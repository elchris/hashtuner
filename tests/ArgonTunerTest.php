<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\ArgonTuner;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;
use ChrisHolland\HashTuner\Tuner;

class ArgonTunerTest extends BaseTunerTest
{
    public function testTunerWithArgonRunTime()
    {
        $tuner = new Tuner(
            $this->getArgonTwoDimensionsTunerStrategy()
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();
        $this->assertResultCorrectness($result, false);
        self::assertSame($this->threads, $result->threads);
    }

    public function testArgonTunerResultsWithDefaults()
    {
        $result = ArgonTuner::getTunedArgonSettings();
        $this->assertResultCorrectness($result);
    }

    public function testArgonTunerWithDefaults()
    {
        $tuner = ArgonTuner::getArgonTuner();
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    public function testArgonTunerWithCustomSpeedAndMemoryLimit()
    {
        $results = ArgonTuner::getTunedArgonSettingsForSpeedAndMemoryLimit(
            0.5,
            1.0,
            256000
        );
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomMemoryLimit()
    {
        $results = ArgonTuner::getTunedArgonSettingsForMemoryLimit(
            256000
        );
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomStrategy()
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