<?php

namespace ChrisHolland\HashTuner\Tuners;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\Exception\UnsupportedPasswordHashingAlgo;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\RunTime\HashRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class ArgonTuner
{
    public const DEFAULT_INITIAL_MEMORY = 32 * 1024;
    public const DEFAULT_INITIAL_ITERATIONS = 3;

    /**
     * ArgonTuner constructor.
     * @param bool $makeAlgoNotExist
     * @throws UnsupportedPasswordHashingAlgo
     */
    public function __construct(bool $makeAlgoNotExist = false)
    {
        $algoExists = defined('PASSWORD_ARGON2ID');
        if ($makeAlgoNotExist) {
            $algoExists = false;
        }
        if (!$algoExists) {
            throw new UnsupportedPasswordHashingAlgo(
                'Argon2id is not supported on your system'
            );
        }
    }

    public function getTunedSettings(): TuningResult
    {
        return $this->getTunedSettingsForSpeed(
            Tuner::DEFAULT_EXECUTION_LOW,
            Tuner::DEFAULT_EXECUTION_HIGH
        );
    }

    public function getTunedSettingsForSpeed(
        float $low,
        float $high
    ): TuningResult {
        return $this->getSettingsForTuner(
            Tuner::getExecutionBounds($low, $high),
            $this->getDefaultArgonRunTime()
        );
    }

    public function getTunedSettingsForMemoryLimit(
        int $hardMemoryLimit
    ): TuningResult {
        return $this->getSettingsForTuner(
            Tuner::getDefaultExecutionBounds(),
            $this->getRunTimeWithMemoryLimit($hardMemoryLimit)
        );
    }

    public function getTunedSettingsForSpeedAndMemoryLimit(
        float $low,
        float $high,
        int $hardMemoryLimit
    ): TuningResult {
        return $this->getSettingsForTuner(
            Tuner::getExecutionBounds($low, $high),
            $this->getRunTimeWithMemoryLimit($hardMemoryLimit)
        );
    }

    public function getTunerForSpeed(float $low, float $high): Tuner
    {
        return $this->getTunerForBoundsAndRunTime(
            Tuner::getExecutionBounds($low, $high),
            $this->getDefaultArgonRunTime()
        );
    }

    public function getTuner() : Tuner
    {
        return $this->getTunerForSpeed(
            Tuner::DEFAULT_EXECUTION_LOW,
            Tuner::DEFAULT_EXECUTION_HIGH
        );
    }

    private function getDefaultArgonRunTime(): ArgonRunTime
    {
        return new ArgonRunTime(
            self::DEFAULT_INITIAL_MEMORY,
            self::DEFAULT_INITIAL_ITERATIONS
        );
    }

    private function getRunTimeWithMemoryLimit(int $hardMemoryLimit): ArgonRunTime
    {
        return new ArgonRunTime(
            self::DEFAULT_INITIAL_MEMORY,
            self::DEFAULT_INITIAL_ITERATIONS,
            $hardMemoryLimit
        );
    }

    private function getTunerForBoundsAndRunTime(
        ExecutionBounds $bounds,
        HashRunTime $runTime
    ): Tuner {
        return new Tuner(
            new TwoDimensionsTunerStrategy(
                $bounds,
                $runTime
            )
        );
    }

    private function getSettingsForTuner(
        ExecutionBounds $bounds,
        HashRunTime $runTime
    ): TuningResult {
        $tuner = $this->getTunerForBoundsAndRunTime($bounds, $runTime);
        $tuner->tune();
        return $tuner->getTuningResult();
    }
}
