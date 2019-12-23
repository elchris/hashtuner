<?php

namespace ChrisHolland\HashTuner;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\TuningResult;
use ChrisHolland\HashTuner\RunTime\ArgonRunTime;
use ChrisHolland\HashTuner\Strategy\TunerStrategy;
use ChrisHolland\HashTuner\Strategy\TwoDimensionsTunerStrategy;

class Tuner
{
    const DEFAULT_EXECUTION_LOW = 0.5;
    const DEFAULT_EXECUTION_HIGH = 1.0;
    const DEFAULT_INITIAL_MEMORY = 128000;
    const DEFAULT_INITIAL_ITERATIONS = 3;
    /**
     * @var TunerStrategy
     */
    private $strategy;

    public function __construct(
        TunerStrategy $strategy
    ) {
        $this->strategy = $strategy;
    }

    public static function getTunedArgonSettings()
    {
        return self::getTunedArgonSettingsForSpeed(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }

    public function tune() : void
    {
        $this->strategy->tune();
    }

    public function getTuningResult() : TuningResult
    {
        return $this->strategy->getTuningResult();
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
        $tuner = new self(
            new TwoDimensionsTunerStrategy(
                self::getDefaultExecutionBounds(),
                new ArgonRunTime(
                    self::DEFAULT_INITIAL_MEMORY,
                    self::DEFAULT_INITIAL_ITERATIONS,
                    $hardMemoryLimit
                )
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
        $tuner = new self(
            new TwoDimensionsTunerStrategy(
                new ExecutionBounds(
                    $low,
                    $high
                ),
                new ArgonRunTime(
                    self::DEFAULT_INITIAL_MEMORY,
                    self::DEFAULT_INITIAL_ITERATIONS,
                    $hardMemoryLimit
                )
            )
        );
        $tuner->tune();
        return $tuner->getTuningResult();
    }

    /**
     * @param float $low
     * @param float $high
     * @return Tuner
     */
    public static function getArgonTunerForSpeed(float $low, float $high): Tuner
    {
        return new self(
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
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }

    public static function getArgonTunerWithStrategy(
        TunerStrategy $strategy
    ) {
        return new self($strategy);
    }

    /**
     * @return ExecutionBounds
     */
    private static function getDefaultExecutionBounds(): ExecutionBounds
    {
        return new ExecutionBounds(
            self::DEFAULT_EXECUTION_LOW,
            self::DEFAULT_EXECUTION_HIGH
        );
    }
}
