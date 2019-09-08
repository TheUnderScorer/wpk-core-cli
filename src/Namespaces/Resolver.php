<?php

namespace UnderScorer\CoreCli\Namespaces;

use Exception;

/**
 * Class Resolver
 * @package UnderScorer\CoreCli\Namespaces
 */
class Resolver
{

    /**
     * Returns base application namespace using composer settings
     *
     * @param string $rootDir
     *
     * @return string
     * @throws Exception
     */
    public static function getBaseNamespace( string $rootDir ): string
    {
        $composerContent = file_get_contents( $rootDir . 'composer.json' );
        $composerJson    = json_decode( $composerContent, true );

        $namespacesPsr4 = $composerJson[ 'autoload' ][ 'psr-4' ];
        $fullNamespace  = array_keys( $namespacesPsr4 )[ 0 ];

        if ( ! $fullNamespace ) {
            throw new Exception( 'Unable to resolve base namespace using composer config.' );
        }

        [ $appNamespace ] = explode( '\\', $fullNamespace );

        return $appNamespace;
    }

}
