<?php

namespace ChrisHolland\HashTuner\Strategy;

use ChrisHolland\HashTuner\TuningResult;

interface TunerStrategy
{
    public function tune(): void;

    public function getTuningResult(): TuningResult;
}
