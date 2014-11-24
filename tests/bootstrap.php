<?php

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    echo 'Could not find "vendor/autoload.php". Did you forget to run "composer install --dev"?'.PHP_EOL;
    exit(1);
}

$loader = require $autoloadFile;
$loader->add('Asoc\Tests\Dadatata', __DIR__, true);