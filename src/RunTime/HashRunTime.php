<?php

namespace ChrisHolland\HashTuner\RunTime;

use ChrisHolland\HashTuner\Exception\FirstDimensionLimitViolation;

interface HashRunTime
{
    public function getInfo(): array;

    public function getFirstDimension(): float;

    public function getExecutionTime(): float;

    /**
     * @throws FirstDimensionLimitViolation
     */
    public function bumpFirstDimension(): void;

    public function getSecondDimension(): int;

    public function bumpSecondDimension(): void;

    public function lowerSecondDimensionOneStep(): void;

    public function getThirdDimension(): int;

    public function getHardMemoryLimitInKilobytes(): int;
}
