<?php


use Illuminate\Container\Container;
use Symfony\Component\Filesystem\Filesystem;
use UnderScorer\CoreCli\Commands\InstallCommand;

$container = new Container();

$container->singleton( Filesystem::class );
$container->bind( Container::class, function () use ( $container ) {
    return $container;
} );

$container
    ->when( [ InstallCommand::class ] )
    ->needs( '$rootDir' )
    ->give( __DIR__ );

return $container;
