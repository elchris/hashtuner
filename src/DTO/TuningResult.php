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

    /**
     * @var array<mixed>
     */
    public $info;

    public function __construct(
        int $hardMemoryLimit,
        ExecutionBounds $range,
        Settings $settings,
        ExecutionInfo $executionInfo
    ) {
        $this->info = $executionInfo->hashInfo;
        $this->settingMemory = (int)$settings->memory;
        $this->settingIterations = $settings->iterations;
        $this->settingThreads = $settings->threads;
        $this->executionTime = $executionInfo->executionTime;
        $this->desiredExecutionLow = $range->lower;
        $this->desiredExecutionHigh = $range->upper;
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

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
