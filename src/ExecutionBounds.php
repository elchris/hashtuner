<?php

namespace ChrisHolland\HashTuner;

class ExecutionBounds
{
    /**
     * @var float
     */
    public $lower;
    /**
     * @var float
     */
    public $upper;

    public function __construct(float $desiredExecutionTimeLowerLimit, float $desiredExecutionTimeUpperLimit)
    {
        $this->lower = $desiredExecutionTimeLowerLimit;
        $this->upper = $desiredExecutionTimeUpperLimit;
    }
}
