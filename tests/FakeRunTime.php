<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\RunTime\HashRunTime;

class FakeRunTime implements HashRunTime
{
    const LOAD_INCREASE = 0.10;
    const LOAD_MULTIPLIER = 1.25;
    const HARD_MEMORY_LIMIT = 4096000;
    /**
     * @var float
     */
    private $memory;
    /**
     * @var float
     */
    private $execTime;
    /**
     * @var int
     */
    private $iterations;

    public function __construct(
        int $initialIterations,
        int $initialMemory,
        float $initialExecTime
    ) {
        $this->iterations = (int)$initialIterations;
        $this->memory = (float)$initialMemory;
        $this->execTime = (float)$initialExecTime;
    }

    public function getFirstDimension() : float
    {
        return $this->memory;
    }

    public function getExecutionTime() : float
    {
        return $this->execTime;
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function bumpFirstDimension() : void
    {
        $targetMemorySetting = $this->memory + (self::LOAD_INCREASE * $this->memory);
        if ($targetMemorySetting > self::HARD_MEMORY_LIMIT) {
            throw new FirstDimensionLimitViolation();
        }
        $this->memory = $targetMemorySetting;
        $this->execTime = $this->execTime + (self::LOAD_INCREASE * $this->execTime);
    }

    public function getSecondDimension() : int
    {
        return $this->iterations;
    }

    public function bumpSecondDimension() : void
    {
        $this->iterations++;
        $this->execTime *= self::LOAD_MULTIPLIER;
    }

    public function lowerSecondDimensionOneStep() : void
    {
        $this->iterations--;
        $this->execTime /= self::LOAD_MULTIPLIER;
    }

    public function info() : string
    {
        return $this->getFirstDimension().':'.$this->getExecutionTime().':'.$this->getSecondDimension();
    }

    public function getThirdDimension() : int
    {
        return 16;
    }
}
