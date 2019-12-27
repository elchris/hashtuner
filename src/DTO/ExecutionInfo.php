<?php

namespace ChrisHolland\HashTuner\DTO;

class ExecutionInfo
{
    /**
     * @var array<mixed>
     */
    public $hashInfo;
    /**
     * @var float
     */
    public $executionTime;

    /**
     * ExecutionInfo constructor.
     * @param array<mixed> $hashInfo
     * @param float $executionTime
     */
    public function __construct(
        array $hashInfo,
        float $executionTime
    ) {
        $this->hashInfo = $hashInfo;
        $this->executionTime = $executionTime;
    }
}
