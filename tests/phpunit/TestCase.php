<?php

namespace UnderScorer\CoreCli\Tests;

use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use UnderScorer\CoreCli\Filesystem\Path;

/**
 * Class TestCase
 * @package UnderScorer\CoreCli\Tests
 */
abstract class TestCase extends PHPUnitTestCase
{

    /**
     * @var Container
     */
    private static $container;

    /**
     * @var string
     */
    private static $rootDir;

    private static $rootDirDotted = '';

    /**
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$container;
    }

    /**
     * @param Container $container
     */
    public static function setContainer( Container $container ): void
    {
        self::$container = $container;
    }

    /**
     * @return string
     */
    public static function getRootDir(): string
    {
        return self::$rootDir;
    }

    /**
     * @param string $rootDir
     */
    public static function setRootDir( string $rootDir ): void
    {
        $rootDirDotted       = Path::converToDotNotation( $rootDir );
        self::$rootDirDotted = $rootDirDotted;

        self::$rootDir = $rootDir;
    }

    /**
     * @return string
     */
    public static function getRootDirDotted(): string
    {
        return self::$rootDirDotted;
    }

    /**
     * @param string $rootDirDotted
     */
    public static function setRootDirDotted( string $rootDirDotted ): void
    {
        self::$rootDirDotted = $rootDirDotted;
    }

}
