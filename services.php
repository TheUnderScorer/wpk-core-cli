<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use UnderScorer\CoreCli\Commands\InstallCommand;
use UnderScorer\CoreCli\Commands\MakeModuleCommand;
use UnderScorer\CoreCli\Filesystem\Filesystem;

$container = new Container();

$container->singleton( Filesystem::class );
$container->singleton( ContainerInterface::class, function () use ( $container ) {
    return $container;
} );

$container
    ->when( [ InstallCommand::class, MakeModuleCommand::class ] )
    ->needs( '$rootDir' )
    ->give( __DIR__ );

return $container;
