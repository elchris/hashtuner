<?php

use ChrisHolland\HashTuner\Tuner;

require_once './vendor/autoload.php';
echo "Settings for Argon2id\n";
print Tuner::getTunedArgonSettings()->toJson();
