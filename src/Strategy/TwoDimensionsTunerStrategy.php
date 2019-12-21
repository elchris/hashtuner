<?php

namespace ChrisHolland\HashTuner\Strategy;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\ExecutionBounds;
use ChrisHolland\HashTuner\RunTime\HashRunTime;
use ChrisHolland\HashTuner\TuningResult;

class TwoDimensionsTunerStrategy implements TunerStrategy
{
    const FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT = 0.75;

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
     * @var HashRunTime
     */
    private $runTime;

    public function __construct(
        ExecutionBounds $bounds,
        HashRunTime $runTime
    ) {
        $this->desiredExecutionTimeLowerLimit = $bounds->lower;
        $this->desiredExecutionTimeUpperLimit = $bounds->upper;
        $this->firstDimensionBumpStopThreshold =
            self::FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT
            *
            $this->getUpper();
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
        return $this->getActualExecutionTime() > $this->getUpper();
    }

    private function hasNotPassedLowerThreshold(): bool
    {
        return $this->getActualExecutionTime() < $this->getLower();
    }

    public function hasReachedFirstDimensionBumpStopThreshold(): bool
    {
        return $this->getActualExecutionTime() >= $this->firstDimensionBumpStopThreshold;
    }

    public function getRunTimeInfo() : string
    {
        return $this->runTime->info();
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    private function bumpFirstDimension() : void
    {
        $this->runTime->bumpFirstDimension();
    }

    public function tuneFirstDimension() : void
    {
        while ($this->hasNotPassedLowerThreshold()
            ||
            (
                $this->isAcceptable()
                &&
                ! $this->hasReachedFirstDimensionBumpStopThreshold()
            )
        ) {
            try {
                $this->bumpFirstDimension();
            } catch (FirstDimensionLimitViolation $e) {
                break;
            }
        }
    }

    private function bumpSecondDimension() : void
    {
        $this->runTime->bumpSecondDimension();
    }

    public function tuneSecondDimensionBeyondAcceptability() : void
    {
        while ($this->hasNotPassedLowerThreshold()
            ||
            $this->isAcceptable()
        ) {
            $this->bumpSecondDimension();
        }
    }

    public function tuneSecondDimensionBackWithinAcceptability() : void
    {
        $this->backTrackSecondDimensionByOne();
    }

    private function backTrackSecondDimensionByOne() : void
    {
        $this->runTime->lowerSecondDimensionOneStep();
    }

    public function tune() : void
    {
        $this->tuneFirstDimension();
        $this->tuneSecondDimensionBeyondAcceptability();
        $this->tuneSecondDimensionBackWithinAcceptability();
    }

    /**
     * @return float
     */
    private function getUpper(): float
    {
        return $this->desiredExecutionTimeUpperLimit;
    }

    /**
     * @return float
     */
    private function getLower(): float
    {
        return $this->desiredExecutionTimeLowerLimit;
    }

    public function getTuningResult() : TuningResult
    {
        return new TuningResult(
            $this->runTime->getFirstDimension(),
            $this->runTime->getSecondDimension(),
            $this->runTime->getThirdDimension(),
            $this->runTime->getExecutionTime()
        );
    }
}
