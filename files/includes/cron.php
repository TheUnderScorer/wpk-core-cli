<?php

use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\Core\Utility\Date;


/**
 * @param AppInterface $app
 *
 * @return void
 * @throws Exception
 *
 */
function registerCrons( AppInterface $app ): void
{

    $config = $app->getPath( 'config' );
    $crons  = require $config . 'schedules.php';

    $cron               = $crons[ 'cron' ];
    $recurrentSchedules = $crons[ 'recurrentSchedules' ];

    foreach ( $cron as $class ) {
        $app->singleton( $class, function ( AppInterface $app ) use ( $class ) {
            return new $class( $app );
        } );
        $app->make( $class );
    }

    foreach ( $recurrentSchedules as $class => $recurrentSchedule ) {

        $recurrence = $recurrentSchedules[ 'recurrence' ] ?? 'daily';
        $start      = $recurrentSchedule[ 'start' ] ?? new Date( '00:00' );

        $app->singleton( $class, function ( AppInterface $app ) use ( $class, $recurrence, $start ) {
            return new $class( $app, $recurrence, $start );
        } );

    }

}


