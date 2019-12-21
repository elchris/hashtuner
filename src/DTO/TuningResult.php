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

    public function __construct(
        float $memory,
        int $iterations,
        int $threads,
        float $executionTime
    ) {
        $this->memory = intval($memory);
        $this->iterations = $iterations;
        $this->threads = $threads;
        $this->executionTime = $executionTime;
    }
}
