<?php

use Dotenv\Dotenv;
use UnderScorer\Core\App;
use WPK\Tests\TestCase;

define( 'TESTS_DIR', __DIR__ );
define( 'DATA_DIR', TESTS_DIR . '/data' );

$dir = __DIR__;

require_once $dir . '/../../vendor/autoload.php';

$testsDir = __DIR__ . '/Suite/tests/phpunit';

if ( ! file_exists( $testsDir ) ) {
    $dotenv = Dotenv::create( $dir );
    $dotenv->load();

    $testsDir = getenv( 'WP_TESTS_DIR' );

    if ( ! $testsDir ) {
        echo 'Error! You need to either setup tests suite using docker-compose or provide path to your own tests suite in WP_TESTS_DIR env variable.';

        return;
    }

}

// Give access to tests_add_filter() function.
require_once $testsDir . '/includes/functions.php';

// disable xdebug backtrace
if ( function_exists( 'xdebug_disable' ) ) {
    xdebug_disable();
}

define( 'WP_PLUGIN_DIR', $dir . '../../../' );

if ( false !== getenv( 'WP_THEMES_DIR' ) ) {
    define( 'WP_THEMES_DIR', getenv( 'WP_THEMES_DIR' ) );
}

// Start up the WP testing environment.
require $testsDir . '/includes/bootstrap.php';

/**
 * @var App $app
 */
$app = require $dir . '/../../index.php';

TestCase::setApp( $app );

do_action( 'plugins_loaded' );


