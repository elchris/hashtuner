<?php

namespace ChrisHolland\HashTuner;

class Tuner
{
    const MEMORY_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT = 0.90;
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
    /**
     * @var float
     */
    private $memoryBumpStopThreshold;

    public function __construct(
        float $desiredExecutionTimeLowerLimit,
        float $desiredExecutionTimeUpperLimit,
        float $actualExecutionTime
    ) {
        $this->actualExecutionTime = $actualExecutionTime;
        $this->desiredExecutionTimeUpperLimit = $desiredExecutionTimeUpperLimit;
        $this->desiredExecutionTimeLowerLimit = $desiredExecutionTimeLowerLimit;
        $this->memoryBumpStopThreshold =
            self::MEMORY_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT
            *
            $this->desiredExecutionTimeUpperLimit;
    }

    public function isAcceptable() : bool
    {
        $answer = true;
        if ($this->exceededUpperLimit()) {
            $answer = false;
        }
        if ($this->hasNotPassedLowerThreshold()) {
            $answer = false;
        }
        return $answer;
    }

    private function exceededUpperLimit(): bool
    {
        return $this->actualExecutionTime > $this->desiredExecutionTimeUpperLimit;
    }

    public function hasNotPassedLowerThreshold(): bool
    {
        return $this->actualExecutionTime < $this->desiredExecutionTimeLowerLimit;
    }

    public function hasReachedMemoryBumpStopThreshold(): bool
    {
        return $this->actualExecutionTime >= $this->memoryBumpStopThreshold;
    }
}
