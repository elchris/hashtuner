<?php

namespace ChrisHolland\HashTuner\DTO;

use JsonException;

class TuningResult
{
    public int $hardMemoryLimit;
    public float $desiredExecutionLow;
    public float $desiredExecutionHigh;
    public int $settingMemory;
    public int $settingIterations;
    public int $settingThreads;
    public float $executionTime;

    /**
     * @var string[]
     */
    public array $info;

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

    /**
     * @throws JsonException
     */
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
