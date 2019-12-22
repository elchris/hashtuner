<?php
require_once 'vendor/autoload.php';

use ChrisHolland\HashTuner\Tuner;

echo "Settings for Argon2id\n";
print Tuner::getTunedArgonSettings()->toJson()."\n";
