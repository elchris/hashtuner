<?php

namespace ChrisHolland\HashTuner;

use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TunerStrategy;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class Tuner
{
    /**
     * @var TunerStrategy
     */
    private $strategy;

    public function __construct(
        TunerStrategy $strategy
    ) {
        $this->strategy = $strategy;
    }

    public static function getTunedArgonSettings()
    {
        $defaultLow = 0.5;
        $defaultHigh = 1.0;

        return self::getTunedArgonSettingsForSpeed($defaultLow, $defaultHigh);
    }

    public function tune() : void
    {
        $this->strategy->tune();
    }

    public function getTuningResult() : TuningResult
    {
        return $this->strategy->getTuningResult();
    }

    public static function getTunedArgonSettingsForSpeed(float $low, float $high): TuningResult
    {
        $tuner = new self(
            new TwoDimensionsTunerStrategy(
                new ExecutionBounds(
                    $low,
                    $high
                ),
                new ArgonRunTime(
                    512000,
                    3
                )
            )
        );
        $tuner->tune();

        return $tuner->getTuningResult();
    }
}
