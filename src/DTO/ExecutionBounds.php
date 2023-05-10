<?php

namespace ChrisHolland\HashTuner\DTO;

class ExecutionBounds
{
    public float $lower;
    public float $upper;

    public function __construct(float $desiredExecutionTimeLowerLimit, float $desiredExecutionTimeUpperLimit)
    {
        $this->lower = $desiredExecutionTimeLowerLimit;
        $this->upper = $desiredExecutionTimeUpperLimit;
    }
}
