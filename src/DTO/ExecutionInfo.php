<?php

namespace ChrisHolland\HashTuner\DTO;

class ExecutionInfo
{
    /**
     * @var array
     */
    public $hashInfo;
    /**
     * @var float
     */
    public $executionTime;

    public function __construct(
        array $hashInfo,
        float $executionTime
    ) {
        $this->hashInfo = $hashInfo;
        $this->executionTime = $executionTime;
    }
}
