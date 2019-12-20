<?php

namespace ChrisHolland\HashTuner;

use ChrisHolland\HashTuner\Test\FakeRunTime;

class Tuner
{
    const MEMORY_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT = 0.90;

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
    private $firstDimensionBumpStopThreshold;
    /**
     * @var FakeRunTime
     */
    private $runTime;

    public function __construct(
        float $desiredExecutionTimeLowerLimit,
        float $desiredExecutionTimeUpperLimit,
        FakeRunTime $runTime
    ) {
        $this->desiredExecutionTimeUpperLimit = $desiredExecutionTimeUpperLimit;
        $this->desiredExecutionTimeLowerLimit = $desiredExecutionTimeLowerLimit;
        $this->firstDimensionBumpStopThreshold =
            self::MEMORY_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT
            *
            $this->desiredExecutionTimeUpperLimit;
        $this->runTime = $runTime;
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

    /**
     * @return float
     */
    public function getActualExecutionTime(): float
    {
        return $this->runTime->getExecutionTime();
    }

    private function exceededUpperLimit(): bool
    {
        return $this->getActualExecutionTime() > $this->desiredExecutionTimeUpperLimit;
    }

    public function hasNotPassedLowerThreshold(): bool
    {
        return $this->getActualExecutionTime() < $this->desiredExecutionTimeLowerLimit;
    }

    public function hasReachedFirstDimensionBumpStopThreshold(): bool
    {
        return $this->getActualExecutionTime() >= $this->firstDimensionBumpStopThreshold;
    }

    public function getRunTimeInfo() : string
    {
        return $this->runTime->getFirstDimension().':'.$this->runTime->getExecutionTime();
    }

    public function bumpFirstDimension() : void
    {
        $this->runTime->bumpFirstDimension();
    }
}
