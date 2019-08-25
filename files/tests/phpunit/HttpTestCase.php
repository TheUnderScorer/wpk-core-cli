<?php

namespace WPK\Tests;

use UnderScorer\Core\App;
use UnderScorer\Core\Hooks\Controllers\AjaxController;
use UnderScorer\Core\Http\Request;
use UnderScorer\Core\Tests\Common\Factories\ControllerFactory;
use WPAjaxDieContinueException;

/**
 * Class TestCase
 * @package UnderScorer\Core\Tests
 */
abstract class HttpTestCase extends TestCase
{

    /**
     * @var App Instance of app that is being tested
     */
    protected static $app;

    /**
     * @var ControllerFactory
     */
    protected static $controllerFactory;

    /**
     * @param string  $controller
     * @param Request $request
     *
     * @return array|null
     */
    protected function makeAjaxCall( string $controller, ?Request $request = null )
    {

        /**
         * @var AjaxController $instance
         */
        $instance = parent::$controllerFactory->make( $controller );

        if ( $request ) {
            $instance->setRequest( $request );
        }

        try {
            $instance->handle();
        } catch ( WPAjaxDieContinueException $e ) {

        }

        // Restore buffer
        ob_start();

        return $this->getLastResponse();

    }

    /**
     * @return array|null
     */
    protected function getLastResponse()
    {
        return json_decode( $this->_last_response, true );
    }

}
