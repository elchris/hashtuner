<?php

namespace ChrisHolland\HashTuner\DTO;

class TuningResult
{
    /**
     * @var int
     */
    public $hardMemoryLimit;
    /**
     * @var float
     */
    public $desiredExecutionLow;
    /**
     * @var float
     */
    public $desiredExecutionHigh;
    /**
     * @var int
     */
    public $settingMemory;
    /**
     * @var int
     */
    public $settingIterations;
    /**
     * @var int
     */
    public $settingThreads;
    /**
     * @var float
     */
    public $executionTime;

    public function __construct(
        int $hardMemoryLimit,
        float $desiredExecutionLow,
        float $desiredExecutionHigh,
        float $memory,
        int $iterations,
        int $threads,
        float $executionTime
    ) {
        $this->settingMemory = (int)$memory;
        $this->settingIterations = $iterations;
        $this->settingThreads = $threads;
        $this->executionTime = $executionTime;
        $this->desiredExecutionLow = $desiredExecutionLow;
        $this->desiredExecutionHigh = $desiredExecutionHigh;
        $this->hardMemoryLimit = $hardMemoryLimit;
    }

    public function toJson() : string
    {
        return json_encode(
            $this->toArray(),
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT,
            512
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
