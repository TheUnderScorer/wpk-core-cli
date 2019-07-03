<?php

namespace WPK\Core;

use Dotenv\Dotenv;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use SuperClosure\Serializer;
use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\Core\Cron\Queue\Queue;
use UnderScorer\Core\Enqueue;
use UnderScorer\Core\Providers\ServiceProvider;
use UnderScorer\Core\Storage\ServiceContainer;

/**
 * @param string $includes
 */
function requireFiles( string $includes ): void
{
    require_once $includes . 'http.php';
    require_once $includes . 'enqueue.php';
    require_once $includes . 'install.php';
    require_once $includes . 'cron.php';
    require_once $includes . 'acf.php';
    require_once $includes . 'enqueue.php';
    require_once $includes . 'migrations.php';
}

/**
 * Handles plugin installation process
 *
 * @param AppInterface $app
 *
 * @throws Exception
 */
function install( AppInterface $app )
{
    $includes = $app->getPath( 'includes' );

    providers( $app );
    requireFiles( $includes );
    setHelpers( $app );
    registerCrons( $app );
    modules( $app );
    controllers( $app );

    $enqueue = $app->make( Enqueue::class );
    enqueue( $enqueue );

    // Create env variables
    $dotenv = Dotenv::create( $app->getPath( '' ) );
    $dotenv->load();

    $app->onActivation( function ( AppInterface $app ) {
        migrations( $app );
    } );

    $app->onDeactivation( function ( AppInterface $app ) {
        migrationsDown( $app );
    } );
}

/**
 * @param AppInterface $app
 *
 * @return void
 * @throws BindingResolutionException
 */
function setHelpers( AppInterface $app ): void
{
    Queue::setSerializer( $app->make( Serializer::class ) );
}

/**
 * Registers app providers
 *
 * @param AppInterface $app
 *
 * @return void
 */
function providers( AppInterface $app ): void
{
    $providers = require $app->getPath( 'config' ) . 'providers.php';

    foreach ( $providers as $providerClass ) {
        /**
         * @var ServiceProvider $provider
         */
        $provider = new $providerClass( $app );

        $provider->register();
    }
}

/**
 * Registers app modules
 *
 * @param AppInterface $app
 *
 * @return void
 */
function modules( AppInterface $app ): void
{
    // Load modules
    $modules = require $app->getPath( 'config' ) . 'modules.php';

    foreach ( $modules as $ID => $module ) {
        new $module( $ID, $app, new ServiceContainer );
    }
}

/**
 * Registers app controllers
 *
 * @param AppInterface $app
 *
 * @return void
 */
function controllers( AppInterface $app ): void
{
    // Load core controllers
    $controllers = require_once $app->getPath( 'config' ) . 'controllers.php';

    add_action( 'plugins_loaded', function () use ( $app, $controllers ) {
        $app->loadControllers( $controllers );
    } );
}
