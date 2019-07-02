<?php

namespace WPK\Tests;

use UnderScorer\Core\App;
use WP_Ajax_UnitTestCase as BaseTestCase;
use WPK\Tests\Common\Factories\ControllerFactory;

/**
 * Class TestCase
 * @package UnderScorer\Core\Tests
 */
abstract class TestCase extends BaseTestCase
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
     * @return App
     */
    public static function getApp(): App
    {
        return self::$app;
    }

    /**
     * @param App $app
     */
    public static function setApp( App $app ): void
    {
        self::$app = $app;

        self::$controllerFactory = new ControllerFactory( $app );
    }

    /**
     * @param string $role
     *
     * @return int
     */
    protected function login( string $role = 'administrator' ): int
    {

        $user = $this->factory()->user->create( [
            'role' => $role,
        ] );

        wp_set_current_user( $user );

        return $user;

    }

}
