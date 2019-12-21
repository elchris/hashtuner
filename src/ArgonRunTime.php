<?php

namespace ChrisHolland\HashTuner;

class ArgonRunTime implements HashRunTime
{
    const MEMORY_INCREASE_PERCENTAGE = 0.10;
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
    private $systemInfo;

    public function __construct(
        int $initialMemory,
        int $initialIterations
    ) {
        $this->systemInfo = new SystemInfo();
        $this->memory = (float)$initialMemory;
        $this->iterations = $initialIterations;
        $this->threads = $this->getThreadsFromSystem();
        $this->execute();
    }

    private function execute() : void
    {
        $start = microtime(true);
        password_hash(
            'i am not secure',
            PASSWORD_ARGON2ID,
            [
                'threads' => $this->threads,
                'memory_cost' => $this->memory,
                'time_cost' => $this->iterations
            ]
        );
        $end = microtime(true);
        $this->execTime = ($end - $start);
    }

    public function getFirstDimension(): float
    {
        return (float)$this->memory;
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
        $limitInKiloBytes = $this->systemInfo->getMemoryLimitInKiloBytes();

        $targetMemory = $this->memory + (self::MEMORY_INCREASE_PERCENTAGE * $this->memory);
        if ($targetMemory > $limitInKiloBytes) {
            throw new FirstDimensionLimitViolation();
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
        $command = "sysctl -n hw.ncpu";
        $cores = intval(shell_exec($command));
        return $cores * 2;
    }
}
