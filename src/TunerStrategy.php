<?php

namespace ChrisHolland\HashTuner;

interface TunerStrategy
{
    public function tune(): void;

    public function getTuningResult(): TuningResult;
}