<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\RunTime\HashRunTime;

class FakeRunTime implements HashRunTime
{
    public const LOAD_INCREASE = 0.10;
    public const LOAD_MULTIPLIER = 1.25;
    public const HARD_MEMORY_LIMIT = 4096000;
    /**
     * @var float
     */
    private $memory;
    /**
     * @var float
     */
    private $execTime;
    /**
     * @var int
     */
    private $iterations;

    /**
     * @var array<mixed>
     */
    private $info;

    public function __construct(
        int $initialIterations,
        int $initialMemory,
        float $initialExecTime
    ) {
        $this->iterations = $initialIterations;
        $this->memory = (float)$initialMemory;
        $this->execTime = $initialExecTime;
        $this->execute();
    }

    public function getFirstDimension() : float
    {
        return $this->memory;
    }

    public function getExecutionTime() : float
    {
        return $this->execTime;
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function bumpFirstDimension() : void
    {
        $targetMemorySetting = $this->memory + (self::LOAD_INCREASE * $this->memory);
        if ($targetMemorySetting > $this->getHardMemoryLimitInKilobytes()) {
            throw new FirstDimensionLimitViolation('Mem Limit violation');
        }
        $this->memory = $targetMemorySetting;

        $this->execute();
        $this->execTime += (self::LOAD_INCREASE * $this->execTime);
    }

    public function getSecondDimension() : int
    {
        return $this->iterations;
    }

    public function bumpSecondDimension() : void
    {
        $this->iterations++;
        $this->execTime *= self::LOAD_MULTIPLIER;
    }

    public function lowerSecondDimensionOneStep() : void
    {
        $this->iterations--;
        $this->execTime /= self::LOAD_MULTIPLIER;
    }

    public function getThirdDimension() : int
    {
        return 1;
    }

    public function getHardMemoryLimitInKilobytes(): int
    {
        return self::HARD_MEMORY_LIMIT;
    }

    public function getInfo(): array
    {
        return $this->info;
    }

    private function execute() : void
    {
        $this->info = [
            'algoName' => 'argon2id',
            'options' => [
                'memory_cost' => (int)$this->getFirstDimension(),
                'time_cost' => $this->getSecondDimension(),
                'threads' => $this->getThirdDimension()
            ]
        ];
    }
}
