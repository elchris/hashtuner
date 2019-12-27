<?php

namespace ChrisHolland\HashTuner\RunTime;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;
use ChrisHolland\HashTuner\SystemInfo;

class ArgonRunTime implements HashRunTime
{
    public const MEMORY_INCREASE_PERCENTAGE = 0.10;
    /**
     * @var float
     */
    private $memory;
    /**
     * @var int
     */
    private $iterations;
    /**
     * @var int
     */
    private $threads;
    /**
     * @var float
     */
    private $execTime;
    /**
     * @var SystemInfo
     */
    private $systemInfo;
    /**
     * @var int|null
     */
    private $memoryLimitInKilobytesOverride;
    /**
     * @var array<mixed>
     */
    private $info;

    public function __construct(
        int $initialMemory,
        int $initialIterations,
        ?int $memoryLimitInKilobytesOverride = null
    ) {
        $this->systemInfo = new SystemInfo();
        $this->memory = (float)$initialMemory;
        $this->iterations = $initialIterations;
        $this->threads = $this->getThreadsFromSystem();
        $this->execute();
        $this->memoryLimitInKilobytesOverride = $memoryLimitInKilobytesOverride;
    }

    private function execute() : void
    {
        $start = microtime(true);
        $hash = (string)password_hash(
            'i am not secure',
            PASSWORD_ARGON2ID,
            [
                'threads' => $this->threads,
                'memory_cost' => (int)$this->memory,
                'time_cost' => $this->iterations
            ]
        );
        $this->info = password_get_info($hash);
        $end = microtime(true);
        $this->execTime = ($end - $start);
    }

    public function getFirstDimension(): float
    {
        return $this->memory;
    }

    public function getExecutionTime(): float
    {
        return $this->execTime;
    }

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function bumpFirstDimension(): void
    {
        $limitInKiloBytes = $this->getHardMemoryLimitInKilobytes();

        $targetMemory = $this->memory + (self::MEMORY_INCREASE_PERCENTAGE * $this->memory);
        if ($targetMemory > $limitInKiloBytes) {
            throw new FirstDimensionLimitViolation('Hard Memory Limit Reached');
        }
        $this->memory = $targetMemory;
        $this->execute();
    }

    public function getSecondDimension(): int
    {
        return $this->iterations;
    }

    public function bumpSecondDimension(): void
    {
        $this->iterations++;
        $this->execute();
    }

    public function lowerSecondDimensionOneStep(): void
    {
        $this->iterations--;
        $this->execute();
    }

    public function getThirdDimension(): int
    {
        return $this->threads;
    }

    private function getThreadsFromSystem() : int
    {
        //hard-coding to 1 because php doesn't support settingThreads
        //return $this->systemInfo->getCores() * 2;
        return 1;
    }
    
    public function getHardMemoryLimitInKilobytes(): int
    {
        if ($this->memoryLimitInKilobytesOverride === null) {
            return $this->systemInfo->getMemoryLimitInKiloBytes();
        }

        return $this->memoryLimitInKilobytesOverride;
    }

    /**
     * @return array<mixed>
     */
    public function getInfo(): array
    {
        return $this->info;
    }
}
