<?php

namespace WPK\Core;

/*
Plugin Name: WPK Core
Plugin URI: https://wpkraken.io/
Description: WPK Core plugin framework
Author: Przemysław Żydek
Author URI: https://wpkraken.io/
Version: 0.1
Text Domain: wpk-core
*/

use Exception;
use UnderScorer\Core\App;
use UnderScorer\Core\Settings;

if ( ! defined( 'CORE_SLUG' ) ) {
    define( 'CORE_SLUG', 'wpk' );
}

$dir  = __DIR__;
$slug = CORE_SLUG;

// Require composer autoloader
require_once $dir . '/vendor/autoload.php';

try {
    $settings = new Settings( $slug );

    $app = new App(
        $slug,
        __FILE__,
        $settings
    );

    return $app;

} catch ( Exception $e ) {
    echo $e->getMessage();
    die();
}


