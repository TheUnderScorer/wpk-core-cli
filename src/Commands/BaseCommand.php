<?php

namespace UnderScorer\CoreCli\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Symfony\Component\Console\Command\Command;
use UnderScorer\CoreCli\Filesystem\Filesystem;

/**
 * Class BaseCommand
 * @package UnderScorer\Core\Cli\Commands
 */
abstract class BaseCommand extends Command
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * BaseCommand constructor.
     *
     * @param ContainerInterface $container
     * @param string             $rootDir
     * @param string|null        $name
     *
     * @throws BindingResolutionException
     */
    public function __construct( ContainerInterface $container, string $rootDir, string $name = null )
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
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return self
     */
    public function setContainer( ContainerInterface $container ): self
    {
        $this->container = $container;

        return $this;
    }

}
