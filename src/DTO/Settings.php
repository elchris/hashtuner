<?php

namespace ChrisHolland\HashTuner\DTO;

class Settings
{
    public int $memory;
    public int $iterations;
    public int $threads;

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
