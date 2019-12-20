<?php

namespace ChrisHolland\HashTuner;

class Tuner
{
    /**
     * @var TwoDimensionsTunerStrategy
     */
    private $strategy;

    public function __construct(
        TwoDimensionsTunerStrategy $strategy
    ) {
        $this->strategy = $strategy;
    }

    public static function getTunedArgonSettings()
    {
        $tuner = new self(
            new TwoDimensionsTunerStrategy(
                new ExecutionBounds(
                    0.5,
                    1.0
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

    public function tune() : void
    {
        $this->strategy->tune();
    }

    public function getTuningResult() : TuningResult
    {
        return $this->strategy->getTuningResult();
    }
}
