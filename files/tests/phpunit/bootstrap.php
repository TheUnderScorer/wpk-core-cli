<?php

use Dotenv\Dotenv;
use UnderScorer\Core\App;
use UnderScorer\Core\Tests\TestCase;

define( 'TESTS_DIR', __DIR__ );
define( 'DATA_DIR', TESTS_DIR . '/data' );

$dir = __DIR__;

require_once $dir . '/../../vendor/autoload.php';

$testsDir = __DIR__ . '/Suite/wordpress-tests-lib';

if ( ! file_exists( $testsDir ) ) {
    echo 'Error! No test suite found.';

    return;
}

// Give access to tests_add_filter() function.
require_once $testsDir . '/includes/functions.php';

// disable xdebug backtrace
if ( function_exists( 'xdebug_disable' ) ) {
    xdebug_disable();
}

define( 'WP_PLUGIN_DIR', $dir . '../../../' );

// Start up the WP testing environment.
require $testsDir . '/includes/bootstrap.php';

/**
 * @var App $app
 */
$app = require $dir . '/../../index.php';

TestCase::setApp( $app );

do_action( 'plugins_loaded' );


