<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Exception\UnsupportedPasswordHashingAlgo;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;
use ChrisHolland\HashTuner\Tuners\ArgonTuner;
use ChrisHolland\HashTuner\Tuners\Tuner;

class ArgonTunerTestCase extends BaseTunerTestCase
{
    public function testArgonAlgoNotSupported(): void
    {
        $this->expectException(UnsupportedPasswordHashingAlgo::class);
        new ArgonTuner(true);
    }

    public function testTunerWithArgonRunTime(): void
    {
        $tuner = new Tuner(
            $this->getArgonTwoDimensionsTunerStrategy()
        );

        $tuner->tune();
        $result = $tuner->getTuningResult();
        $this->assertResultCorrectness($result, false);
        self::assertSame($this->threads, $result->settingThreads);
    }

    public function testArgonTunerResultsWithDefaults(): void
    {
        $result = (new ArgonTuner())->getTunedSettings();
        $this->assertResultCorrectness($result);
    }

    public function testArgonTunerWithDefaults(): void
    {
        $tuner = (new ArgonTuner())->getTuner();
        $tuner->tune();
        $this->assertResultCorrectness($tuner->getTuningResult(), false);
    }

    public function testArgonTunerWithCustomSpeedAndMemoryLimit(): void
    {
        $results = (new ArgonTuner())->getTunedSettingsForSpeedAndMemoryLimit(
            0.5,
            1.0,
            256000
        );
        $this->assertResultCorrectness($results);
    }

    public function testArgonTunerWithCustomMemoryLimit(): void
    {
        $results = (new ArgonTuner())->getTunedSettingsForMemoryLimit(
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
