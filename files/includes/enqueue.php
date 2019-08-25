<?php

use UnderScorer\Core\Enqueue;

function enqueue( Enqueue $enqueue ): void {

    $enqueue
        ->enqueueScript( [
            'slug'     => 'wpk-app',
            'fileName' => 'wpk-app',
            'inFooter' => true,
        ] );

    $enqueue::outputVars( [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    ], 'wpkCoreVars' );

}

