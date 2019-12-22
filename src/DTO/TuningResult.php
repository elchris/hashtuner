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

    public function toJson() : string
    {
        return json_encode(
            get_object_vars($this),
            JSON_PRETTY_PRINT
        );
    }
}
