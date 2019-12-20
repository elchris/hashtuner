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

    public function tune() : void
    {
        $this->strategy->tune();
    }

    public function getTuningResult() : TuningResult
    {
        return $this->strategy->getTuningResult();
    }
}
