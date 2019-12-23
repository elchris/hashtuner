<?php
require_once 'vendor/autoload.php';

use ChrisHolland\HashTuner\Tuners\ArgonTuner;

if (! isset($argv[1])) {
    echo "Settings for Argon2id\n";
    print ArgonTuner::getTunedArgonSettings()->toJson() . "\n";
} elseif (!isset($argv[2])) {
    $memLimit = (int)$argv[1];
    echo "Settings for Argon2id with $memLimit kilobytes memory limit\n";
    print ArgonTuner::getTunedArgonSettingsForMemoryLimit($memLimit)->toJson() . "\n";
} elseif (isset($argv[1], $argv[2], $argv[3])) {
    $memLimit = (int)$argv[1];
    $low = (float)$argv[2];
    $high = (float)$argv[3];
    echo "Settings for Argon2id
    with $memLimit kilobytes memory limit
    and execution time range in seconds: $low - $high\n";
    print ArgonTuner::getTunedArgonSettingsForSpeedAndMemoryLimit(
        $low,
        $high,
        $memLimit
    )->toJson() . "\n";
}
