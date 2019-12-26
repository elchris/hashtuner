<?php

namespace ChrisHolland\HashTuner\Test;

use ChrisHolland\HashTuner\DTO\ExecutionBounds;
use ChrisHolland\HashTuner\DTO\ExecutionInfo;
use ChrisHolland\HashTuner\DTO\Settings;
use ChrisHolland\HashTuner\DTO\TuningResult;
use PHPUnit\Framework\TestCase;

class TuningResultTest extends TestCase
{
    public function testJsonOutput(): void
    {
        $result = $this->getTuningResult();

        self::assertIsArray($result->info);
        self::assertSame(256000, $result->hardMemoryLimit);
        self::assertSame(0.5, $result->desiredExecutionLow);
        self::assertSame(1.0, $result->desiredExecutionHigh);
        self::assertSame(128000, $result->settingMemory);
        self::assertSame(4, $result->settingIterations);
        self::assertSame(1, $result->settingThreads);
        self::assertSame(.90, $result->executionTime);
        $json = $result->toJson();
        self::assertIsString($json);
        print $json;
    }

    public function testArrayOutput() : void
    {
        $result = $this->getTuningResult();
        $array = $result->toArray();
        self::assertArrayHasKey('hardMemoryLimit', $array);
        self::assertArrayHasKey('info', $array);
        self::assertIsArray($array);
    }

    /**
     * @return TuningResult
     */
    private function getTuningResult(): TuningResult
    {
        $range = new ExecutionBounds(
            0.5,
            1.0
        );

        $settings = new Settings(
            128000,
            4,
            1
        );

        $executionInfo = new ExecutionInfo(
            ['algoName' => 'argon2id'],
            0.90
        );

        return new TuningResult(
            256000,
            $range,
            $settings,
            $executionInfo
        );
    }
}
