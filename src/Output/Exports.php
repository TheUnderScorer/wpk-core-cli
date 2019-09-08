<?php

namespace UnderScorer\CoreCli\Output;

/**
 * Class Exports
 * @package UnderScorer\CoreCli\Output
 */
class Exports
{
    /**
     * @param      $expression
     * @param bool $return
     *
     * @return mixed|string|string[]|null
     * @link https://gist.github.com/Bogdaan/ffa287f77568fcbb4cffa0082e954022
     *
     */
    public static function varExport( $expression, $return = false )
    {
        $export = var_export( $expression, true );
        $export = preg_replace( "/^([ ]*)(.*)/m", '$1$1$2', $export );
        $array  = preg_split( "/\r\n|\n|\r/", $export );
        $array  = preg_replace( [ "/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/" ], [ null, ']$1', ' => [' ], $array );
        $export = join( PHP_EOL, array_filter( [ "[" ] + $array ) );
        if ( (bool) $return ) {
            return $export;
        } else {
            echo $export;

            return null;
        }
    }
}
