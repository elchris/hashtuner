<?php

namespace ChrisHolland\HashTuner\Test;

class FakeRunTime
{
    const LOAD_INCREASE = 0.10;
    const LOAD_MULTIPLIER = 1.25;
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

    public function bumpFirstDimension()
    {
        $this->memory = $this->memory + (self::LOAD_INCREASE * $this->memory);
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

    public function lowerSecondDimensionOneStep()
    {
        $this->iterations--;
        $this->execTime /= self::LOAD_MULTIPLIER;
    }

    public function info() : string
    {
        return $this->getFirstDimension().':'.$this->getExecutionTime().':'.$this->getSecondDimension();
    }
}
