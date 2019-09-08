<?php

namespace UnderScorer\CoreCli\Tests\Filesystem;

use UnderScorer\CoreCli\Filesystem\Path;
use UnderScorer\CoreCli\Tests\TestCase;

/**
 * Class PathTest
 * @package UnderScorer\CoreCli\Tests\Filesystem
 */
final class PathTest extends TestCase
{

    /**
     * @return void
     */
    public function testJoin(): void
    {
        $dir          = __DIR__;
        $expectedPath = $dir . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'file';
        $receivedPath = Path::join($dir, 'test.file');

        $this->assertEquals($expectedPath, $receivedPath);
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $dir          = __DIR__;
        $expectedPath = $dir . DIRECTORY_SEPARATOR . 'test';
        $receivedPath = Path::get( "{$dir}.test" );

        $this->assertEquals( $expectedPath, $receivedPath );
    }
}
