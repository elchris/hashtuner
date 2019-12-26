<?php

namespace ChrisHolland\HashTuner\DTO;

class Settings
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

    public function __construct(
        int $memory,
        int $iterations,
        int $threads
    ) {
        $this->memory = $memory;
        $this->iterations = $iterations;
        $this->threads = $threads;
    }
}
