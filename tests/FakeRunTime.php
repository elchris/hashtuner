<?php

namespace ChrisHolland\HashTuner\Test;

class FakeRunTime
{
    const LOAD_INCREASE = 0.10;
    /**
     * @var float
     */
    private $memory;
    /**
     * @var float
     */
    private $execTime;

    public function __construct(
        int $initialMemory,
        float $initialExecTime
    ) {
        $this->memory = (float)$initialMemory;
        $this->execTime = $initialExecTime;
    }

    public function getMemory()
    {
        return $this->memory;
    }

    public function getExecutionTime()
    {
        return $this->execTime;
    }

    public function bumpMemory()
    {
        $this->memory = $this->memory + (self::LOAD_INCREASE * $this->memory);
        $this->execTime = $this->execTime + (self::LOAD_INCREASE * $this->execTime);
    }
}
