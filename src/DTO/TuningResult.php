<?php

namespace ChrisHolland\HashTuner\DTO;

class TuningResult
{
    /**
     * @var int
     */
    public $memory;
    /**
     * @var int
     */
    public $iterations;
    /**
     * @var int
     */
    public $threads;
    /**
     * @var float
     */
    public $executionTime;
    /**
     * @var float
     */
    public $desiredExecutionLow;
    /**
     * @var float
     */
    public $desiredExecutionHigh;

    public function __construct(
        float $desiredExecutionLow,
        float $desiredExecutionHigh,
        float $memory,
        int $iterations,
        int $threads,
        float $executionTime
    ) {
        $this->memory = (int)$memory;
        $this->iterations = $iterations;
        $this->threads = $threads;
        $this->executionTime = $executionTime;
        $this->desiredExecutionLow = $desiredExecutionLow;
        $this->desiredExecutionHigh = $desiredExecutionHigh;
    }

    public function toJson() : string
    {
        return json_encode(get_object_vars($this), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512);
    }
}
