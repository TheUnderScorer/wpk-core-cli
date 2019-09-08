<?php

use UnderScorer\CoreCli\Tests\TestCase;

$dir     = __DIR__;
$rootDir = $dir . '/../../';

require_once $rootDir . 'vendor/autoload.php';

$container = require $rootDir . 'services.php';

TestCase::setContainer( $container );
TestCase::setRootDir( $rootDir );
