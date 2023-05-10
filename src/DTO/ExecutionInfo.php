<?php

namespace ChrisHolland\HashTuner\DTO;

class ExecutionInfo
{
    /**
     * @var string[]
     */
    public array $hashInfo;
    /**
     * @var float
     */
    public float $executionTime;

    /**
     * @param string[] $hashInfo
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
