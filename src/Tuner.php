<?php

namespace ChrisHolland\HashTuner;

class Tuner
{
    /**
     * @var float
     */
    private $actualExecutionTime;
    /**
     * @var float
     */
    private $desiredExecutionTimeUpperLimit;
    /**
     * @var float
     */
    private $desiredExecutionTimeLowerLimit;

    public function __construct(
        float $desiredExecutionTimeLowerLimit,
        float $desiredExecutionTimeUpperLimit,
        float $actualExecutionTime
    ) {
        $this->actualExecutionTime = $actualExecutionTime;
        $this->desiredExecutionTimeUpperLimit = $desiredExecutionTimeUpperLimit;
        $this->desiredExecutionTimeLowerLimit = $desiredExecutionTimeLowerLimit;
    }

    public function isSuccessFul() : bool
    {
        $answer = true;
        if ($this->actualExecutionTime > $this->desiredExecutionTimeUpperLimit) {
            $answer = false;
        }
        if ($this->actualExecutionTime < $this->desiredExecutionTimeLowerLimit) {
            $answer = false;
        }
        return $answer;
    }
}
