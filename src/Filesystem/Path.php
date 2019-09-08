<?php

namespace UnderScorer\CoreCli\Filesystem;

/**
 * Class Path
 * @package UnderScorer\CoreCli\Filesystem
 */
class Path
{

    /**
     * @param string $path
     *
     * @return string
     */
    public static function converToDotNotation( string $path ): string
    {
        return str_replace( [ '/', '\\' ], '.', $path );
    }

    /**
     * Joins given paths with directory separator
     *
     * @param string[] ...$paths
     *
     * @return string
     */
    public static function join( ...$paths ): string
    {
        $formattedPath = array_map( function ( string $pathPart ) {
            return self::get( $pathPart );
        }, $paths );

        return implode( DIRECTORY_SEPARATOR, $formattedPath );
    }

    /**
     * Formats directory path provided in dot notation into actual path
     *
     * @param string $path
     *
     * @return string
     */
    public static function get( string $path ): string
    {
        $formattedPath = explode( '.', $path );

        return implode( DIRECTORY_SEPARATOR, $formattedPath );
    }

}
