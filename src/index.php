<?php
require_once 'vendor/autoload.php';
use ChrisHolland\HashTuner\Tuners\ArgonTuner;

function processAlgo(string $output): void
{
    print $output."\n";
}
$argon = new ArgonTuner();
if (! isset($argv[1])) {
    processAlgo(
        $argon->getTunedSettings()->toJson()
    );
} elseif (! isset($argv[2])) {
    $memLimit = (int)$argv[1];
    processAlgo(
        $argon->getTunedSettingsForMemoryLimit($memLimit)->toJson()
    );
} elseif (isset($argv[1], $argv[2], $argv[3])) {
    $memLimit = (int)$argv[1];
    $low = (float)$argv[2];
    $high = (float)$argv[3];
    processAlgo(
        $argon->getTunedSettingsForSpeedAndMemoryLimit(
            $low,
            $high,
            $memLimit
        )->toJson()
    );
}
