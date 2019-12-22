<?php
$pharFile = 'hashtuner.phar';

// clean up
if (file_exists($pharFile)) {
    unlink($pharFile);
}
if (file_exists($pharFile . '.gz')) {
    unlink($pharFile . '.gz');
}

// create phar
$p = new Phar($pharFile);

// creating our library using whole directory
$p->buildFromDirectory('src/');

// pointing main file which requires all classes
$p->setDefaultStub('index.php');

// plus - compressing it into gzip
$p->compress(Phar::GZ);

echo "$pharFile successfully created\n";
