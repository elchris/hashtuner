<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\TuningResult;
use PHPUnit\Framework\TestCase;

class TuningResultTest extends TestCase
{
    public function testJsonOutput()
    {
        $result = new TuningResult(
            128000,
            4,
            16,
            0.90
        );

        $json = $result->toJson();
        self::assertIsString($json);
        print $json;
    }
}
