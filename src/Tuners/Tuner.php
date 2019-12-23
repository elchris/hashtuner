<?php

namespace ChrisHolland\HashTuner\Tuners;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Strategy\TunerStrategy;

class Tuner
{
    const DEFAULT_EXECUTION_LOW = 0.5;
    const DEFAULT_EXECUTION_HIGH = 1.0;

    /**
     * @var TunerStrategy
     */
    private $strategy;

    public function __construct(
        TunerStrategy $strategy
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

    public static function getTunerWithStrategy(
        TunerStrategy $strategy
    ) {
        return new self($strategy);
    }

    public static function getDefaultExecutionBounds(): ExecutionBounds
    {
        return new ExecutionBounds(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }
}
