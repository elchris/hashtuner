<?php

namespace ChrisHolland\HashTuner\DTO;

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
