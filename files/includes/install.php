<?php

use Dotenv\Dotenv;
use SuperClosure\Serializer;
use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\Core\Cron\Queue\Queue;
use UnderScorer\Core\Enqueue;
use UnderScorer\Core\Providers\ServiceProvider;
use UnderScorer\Core\Storage\ServiceContainer;

function requireFiles( string $includes ): void
{

    require_once $includes . 'http.php';
    require_once $includes . 'enqueue.php';
    require_once $includes . 'install.php';
    require_once $includes . 'cron.php';
    require_once $includes . 'acf.php';
    require_once $includes . 'enqueue.php';

}

/**
 * Handles plugin installation process
 *
 * @param AppInterface $app
 *
 * @throws Exception
 *
 */
function install( AppInterface $app )
{

    $includes = $app->getPath( 'includes' );
    $config   = $app->getPath( 'config' );

    $providers = require $config . '/providers.php';

    foreach ( $providers as $providerClass ) {
        /**
         * @var ServiceProvider $provider
         */
        $provider = new $providerClass( $app );

        $provider->register();
    }

    /**
     * @var Enqueue $enqueue
     */
    $enqueue = $app->make( Enqueue::class );

    // Include required files
    requireFiles( $includes );

    Queue::setSerializer( $app->make( Serializer::class ) );

    // Load core scripts and styles
    enqueue( $enqueue );

    // Register cron tasks
    registerCrons( $app );

    // Load modules
    $modules = require $config . 'modules.php';
    foreach ( $modules as $ID => $module ) {
        new $module( $ID, $app, new ServiceContainer );
    }

    // Load core controllers
    $controllers = require_once $config . 'controllers.php';

    add_action( 'plugins_loaded', function () use ( $app, $controllers ) {
        $app->loadControllers( $controllers );
    } );

    $dotenv = Dotenv::create( $app->getPath( '' ) );
    $dotenv->load();

}
