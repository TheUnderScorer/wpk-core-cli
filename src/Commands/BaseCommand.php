<?php

namespace UnderScorer\CoreCli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class BaseCommand
 * @package UnderScorer\Core\Cli\Commands
 */
abstract class BaseCommand extends Command
{

    /**
     * @var FileSystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * BaseCommand constructor.
     *
     * @param Filesystem  $filesystem
     * @param string      $rootDir
     * @param string|null $name
     */
    public function __construct( Filesystem $filesystem, string $rootDir, string $name = null )
    {
        parent::__construct( $name );

        $this->filesystem = $filesystem;
        $this->rootDir    = $rootDir;
    }

}
