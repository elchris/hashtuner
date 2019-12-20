<?php

namespace ChrisHolland\HashTuner;

interface HashRunTime
{
    public function getFirstDimension(): float;

    public function getExecutionTime(): float;

    public function bumpFirstDimension(): void;

    public function getSecondDimension(): int;

    public function bumpSecondDimension(): void;

    public function lowerSecondDimensionOneStep(): void;

    public function getThirdDimension(): int;
}