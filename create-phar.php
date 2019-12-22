<?php
$pharFile = 'hashtuner.phar';

// clean up
if (file_exists($pharFile)) {
    unlink($pharFile);
}

// create phar
$p = new Phar($pharFile);

// creating our library using whole directory
$p->buildFromDirectory('src/');

// pointing main file which requires all classes
$p->setDefaultStub('index.php');

echo "$pharFile successfully created\n";
