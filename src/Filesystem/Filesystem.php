<?php

namespace UnderScorer\CoreCli\Filesystem;

use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

/**
 * Class Filesystem
 * @package UnderScorer\CoreCli\Tests\Filesystem
 */
class Filesystem extends SymfonyFileSystem
{

    /**
     * @param string $path
     *
     * @return string|null
     */
    public function getContents( string $path ): ?string
    {
        return @file_get_contents( $path );
    }

    /**
     * Requires file and returns it's content (if any)
     *
     * @param string $file
     *
     * @return mixed
     */
    public function require( string $file )
    {
        return require $file;
    }

    /**
     * @return string
     */
    public function getCwd(): string
    {
        return getcwd();
    }

}
