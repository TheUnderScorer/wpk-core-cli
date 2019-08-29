<?php

namespace WPK\Core;

/*
Plugin Name: WPK Core
Plugin URI: https://wpkraken.io/
Description: WPK Core plugin framework
Author: WP Przemysław Żydek
Author URI: https://wpkraken.io/
Version: 1.3.6
Text Domain: wpk-core
*/

use Exception;
use UnderScorer\Core\AcfSettings;
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

    $settings = function_exists( 'get_field' ) ?
        new AcfSettings( $slug ) :
        new Settings( $slug );

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


