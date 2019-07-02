<?php

namespace WPK\Tests\Common\Factories;

use UnderScorer\Core\App;
use UnderScorer\Core\Hooks\Controllers\Controller;

/**
 * @author Przemysław Żydek
 */
class ControllerFactory
{

    /**
     * @var App
     */
    protected $app;

    /**
     * ControllerFactory constructor.
     *
     * @param App $app
     */
    public function __construct( App $app )
    {
        $this->app = $app;
    }

    /**
     * @param string $class
     *
     * @return Controller
     */
    public function make( string $class ): Controller
    {

        $controller = new $class( $this->app );
        $this->app->setupController( $controller );

        return $controller;

    }

}
