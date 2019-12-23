<?php

namespace ChrisHolland\HashTuner\Tuners;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class ArgonTuner
{
    public const DEFAULT_INITIAL_MEMORY = 32 * 1024;
    public const DEFAULT_INITIAL_ITERATIONS = 3;

    public static function getTunedArgonSettings(): TuningResult
    {
        return self::getTunedArgonSettingsForSpeed(
            Tuner::DEFAULT_EXECUTION_LOW,
            Tuner::DEFAULT_EXECUTION_HIGH
        );
    }

    public static function getTunedArgonSettingsForSpeed(
        float $low,
        float $high
    ): TuningResult {
        $tuner = self::getArgonTunerForSpeed($low, $high);
        $tuner->tune();

        return $tuner->getTuningResult();
    }

    public static function getTunedArgonSettingsForMemoryLimit(
        int $hardMemoryLimit
    ): TuningResult {
        $tuner = new Tuner(
            new TwoDimensionsTunerStrategy(
                Tuner::getDefaultExecutionBounds(),
                self::getArgonRunTimeWithMemoryLimit($hardMemoryLimit)
            )
        );
        $tuner->tune();
        return $tuner->getTuningResult();
    }

    public static function getTunedArgonSettingsForSpeedAndMemoryLimit(
        float $low,
        float $high,
        int $hardMemoryLimit
    ): TuningResult {
        $tuner = new Tuner(
            new TwoDimensionsTunerStrategy(
                new ExecutionBounds(
                    $low,
                    $high
                ),
                self::getArgonRunTimeWithMemoryLimit($hardMemoryLimit)
            )
        );
        $tuner->tune();
        return $tuner->getTuningResult();
    }

    public static function getArgonTunerForSpeed(float $low, float $high): Tuner
    {
        return new Tuner(
            new TwoDimensionsTunerStrategy(
                new ExecutionBounds(
                    $low,
                    $high
                ),
                new ArgonRunTime(
                    self::DEFAULT_INITIAL_MEMORY,
                    self::DEFAULT_INITIAL_ITERATIONS
                )
            )
        );
    }

    public static function getArgonTuner() : Tuner
    {
        return self::getArgonTunerForSpeed(
            Tuner::DEFAULT_EXECUTION_LOW,
            Tuner::DEFAULT_EXECUTION_HIGH
        );
    }

    private static function getArgonRunTimeWithMemoryLimit(int $hardMemoryLimit): ArgonRunTime
    {
        return new ArgonRunTime(
            self::DEFAULT_INITIAL_MEMORY,
            self::DEFAULT_INITIAL_ITERATIONS,
            $hardMemoryLimit
        );
    }
}
