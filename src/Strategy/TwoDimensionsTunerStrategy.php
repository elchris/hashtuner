<?php

namespace ChrisHolland\HashTuner\Strategy;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\ExecutionInfo;
use ChrisHolland\HashTuner\DTO\Settings;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\RunTime\HashRunTime;

class TwoDimensionsTunerStrategy implements TunerStrategy
{
    public const FIRST_DIMENSION_BUMP_STOP_PERCENTAGE_OF_UPPER_LIMIT = 0.75;

    private float $desiredExecutionTimeUpperLimit;
    private float $desiredExecutionTimeLowerLimit;
    private float $firstDimensionBumpStopThreshold;
    private HashRunTime $runTime;

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

    /**
     * @throws FirstDimensionLimitViolation
     */
    private function bumpFirstDimension() : void
    {
        $this->runTime->bumpFirstDimension();
    }

    public function tuneFirstDimension() : void
    {
        while ($this->mustIncreaseFirstDimension()) {
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
        while ($this->mustIncreaseSecondDimension()) {
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
        $range = new ExecutionBounds(
            $this->desiredExecutionTimeLowerLimit,
            $this->desiredExecutionTimeUpperLimit
        );

        $settings = new Settings(
            (int)$this->runTime->getFirstDimension(),
            $this->runTime->getSecondDimension(),
            $this->runTime->getThirdDimension()
        );

        $info = new ExecutionInfo(
            $this->runTime->getInfo(),
            $this->runTime->getExecutionTime()
        );

        return new TuningResult(
            $this->runTime->getHardMemoryLimitInKilobytes(),
            $range,
            $settings,
            $info
        );
    }

    private function hasNotYetReachedFirstDimensionLimit(): bool
    {
        return $this->isAcceptable()
            &&
            !$this->hasReachedFirstDimensionBumpStopThreshold();
    }

    private function mustIncreaseFirstDimension(): bool
    {
        return $this->hasNotPassedLowerThreshold()
            ||
            $this->hasNotYetReachedFirstDimensionLimit();
    }

    private function mustIncreaseSecondDimension(): bool
    {
        return $this->hasNotPassedLowerThreshold()
            ||
            $this->isAcceptable();
    }
}
