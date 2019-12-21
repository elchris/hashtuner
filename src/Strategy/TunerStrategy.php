<?php

namespace ChrisHolland\HashTuner\Strategy;

use ChrisHolland\HashTuner\DTO\TuningResult;

interface TunerStrategy
{
    public function tune(): void;

    public function getTuningResult(): TuningResult;
}
