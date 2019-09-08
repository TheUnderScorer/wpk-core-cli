<?php

namespace UnderScorer\CoreCli\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use UnderScorer\CoreCli\Filesystem\Path;

/**
 * Class Install
 * @package UnderScorer\Core\Cli\Commands
 */
final class InstallCommand extends BaseCommand
{

    /**
     * @var string
     */
    const PLUGIN_DIR = 'plugin_dir';

    /**
     * @var string
     */
    protected static $defaultName = 'app:install';

    /**
     * @var string
     */
    protected $targetDir = '';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription( 'Installs wpk-core plugin' )
            ->addArgument(
                self::PLUGIN_DIR,
                InputArgument::OPTIONAL,
                'Plugin directory name',
                'wpk-core'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $this->targetDir = Path::join( getcwd(), $input->getArgument( self::PLUGIN_DIR ) );

        $output->writeln( "Creating plugin files at {$this->targetDir}" );

        $this->createTargetDir();
        $this->copyPlugin();

        $output->writeln( 'Installing composer dependencies...' );
        $this->installDependencies( $output );

        $output->writeln( 'Plugin created!' );
        $output->writeln( '<comment>Now go and create something amazing! :)</comment>' );
    }

    /**
     * @return void
     */
    protected function createTargetDir(): void
    {
        if ( ! $this->getFilesystem()->exists( $this->targetDir ) ) {
            $this->getFilesystem()->mkdir( $this->targetDir );
        }
    }

    /**
     * @return void
     */
    protected function copyPlugin(): void
    {
        $filesDir = $this->getFilesDir();

        $this->getFilesystem()->mirror( $filesDir, $this->targetDir );
    }

    /**
     * @return string
     */
    protected function getFilesDir(): string
    {
        return Path::join( $this->getRootDir(), 'files.plugin' );
    }

    /**
     * @param OutputInterface $output
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function installDependencies( OutputInterface $output ): void
    {
        $this->handleComposer( $output );
        $this->handleNPM( $output );
    }

    /**
     * @param OutputInterface $output
     *
     * @throws BindingResolutionException
     */
    protected function handleComposer( OutputInterface $output ): void
    {
        $composer = $this->findComposer();

        $commands = [
            "$composer install --prefer-dist --no-interaction",
        ];

        $this->handleProcess( $commands, $output );
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        $composerPath = getcwd() . '/composer.phar';
        if ( file_exists( $composerPath ) ) {
            return '"' . PHP_BINARY . '" ' . $composerPath;
        }

        return 'composer';
    }

    /**
     * Calls process with given commands
     *
     * @param array           $commands
     * @param OutputInterface $output
     *
     * @throws BindingResolutionException
     */
    protected function handleProcess( array $commands, OutputInterface $output ): void
    {
        $process = $this->getContainer()->make( Process::class, [
            'command' => implode( '&&', $commands ),
            'cwd'     => $this->targetDir,
            'env'     => null,
            'input'   => null,
            'timeout' => false,
        ] );

        $process->run( function ( $type, $line ) use ( $output ) {
            $output->write( $line );
        } );

        if ( ! $process->isSuccessful() ) {
            throw new ProcessFailedException( $process );
        }
    }

    /**
     * @param OutputInterface $output
     *
     * @throws BindingResolutionException
     */
    protected function handleNPM( OutputInterface $output ): void
    {
        $npm = 'npm';

        $commands = [
            "$npm install",
        ];

        $this->handleProcess( $commands, $output );
    }

}
