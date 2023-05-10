<?php

namespace ChrisHolland\HashTuner\Tuners;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Strategy\TunerStrategy;

class Tuner
{
    public const DEFAULT_EXECUTION_LOW = 0.5;
    public const DEFAULT_EXECUTION_HIGH = 1.0;

    private TunerStrategy $strategy;

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
    ): Tuner {
        return new self($strategy);
    }

    public static function getDefaultExecutionBounds(): ExecutionBounds
    {
        return self::getExecutionBounds(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }

    public static function getExecutionBounds(float $low, float $high): ExecutionBounds
    {
        return new ExecutionBounds(
            $low,
            $high
        );
    }
}
