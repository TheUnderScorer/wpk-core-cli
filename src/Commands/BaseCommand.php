<?php

namespace UnderScorer\CoreCli\Commands;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class BaseCommand
 * @package UnderScorer\Core\Cli\Commands
 */
abstract class BaseCommand extends Command
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var FileSystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * BaseCommand constructor.
     *
     * @param Container   $container
     * @param string      $rootDir
     * @param string|null $name
     *
     * @throws BindingResolutionException
     */
    public function __construct( Container $container, string $rootDir, string $name = null )
    {
        parent::__construct( $name );

        $this->container  = $container;
        $this->filesystem = $container->make( Filesystem::class );
        $this->rootDir    = $rootDir;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return self
     */
    public function setFilesystem( Filesystem $filesystem ): self
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     *
     * @return self
     */
    public function setRootDir( string $rootDir ): self
    {
        $this->rootDir = $rootDir;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer( Container $container ): self
    {
        $this->container = $container;

        return $this;
    }

}
