<?php

namespace WPK\Core;

use Exception;
use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\Core\Database\Migrations\Migration;

/**
 * Handles database migrations
 *
 * @param AppInterface $app
 *
 * @return void
 * @throws Exception
 */
function migrations( AppInterface $app ): void
{
    $migrations = require_once $app->getPath( 'config' ) . '/migrations.php';

    foreach ( $migrations as $migrationClass ) {

        /**
         * @var Migration $migration
         */
        $migration = $app->make( $migrationClass );

        $migration->up();
    }
}

/**
 * Handles database migrations cleanup
 *
 * @param AppInterface $app
 *
 * @return void
 * @throws Exception
 */
function migrationsDown( AppInterface $app ): void
{
    $migrations = require_once $app->getPath( 'config' ) . '/migrations.php';

    foreach ( $migrations as $migrationClass ) {

        /**
         * @var Migration $migration
         */
        $migration = $app->make( $migrationClass );

        $migration->down();
    }
}
