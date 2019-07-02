<?php

namespace UnderScorer\CoreCli\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


/**
 * Class Install
 * @package UnderScorer\Core\Cli\Commands
 */
class InstallCommand extends BaseCommand
{

    /**
     * @var string
     */
    const PLUGIN_DIR = 'plugin_dir';

    /**
     * @var string
     */
    protected $commandName = 'app:install';

    /**
     * @var string
     */
    protected $commandDescription = 'Installs wpk-core plugin.';

    /**
     * @var string
     */
    protected $targetDir = '';

    /**
     * @var string
     */
    protected $pluginName = 'WPK Core';

    /**
     * @var string
     */
    protected $pluginDescription = 'WPK Core plugin';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName( $this->commandName )
            ->setDescription( $this->commandDescription )
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
     * @return int|void|null
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $this->targetDir = getcwd() . DIRECTORY_SEPARATOR . $input->getArgument( self::PLUGIN_DIR );

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
        if ( ! $this->filesystem->exists( $this->targetDir ) ) {
            $this->filesystem->mkdir( $this->targetDir );
        }
    }

    /**
     * @return void
     */
    protected function copyPlugin(): void
    {
        $filesDir = $this->getFilesDir();

        $this->filesystem->mirror( $filesDir, $this->targetDir );
    }

    /**
     * @return string
     */
    protected function getFilesDir(): string
    {
        return $this->rootDir . DIRECTORY_SEPARATOR . 'files';
    }


    /**
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function installDependencies( OutputInterface $output ): void
    {
        $composer = $this->findComposer();

        $commands = [
            "$composer install --prefer-dist --no-interaction",
        ];

        $process = new Process( implode( '&&', $commands ), $this->targetDir );

        $process->run( function ( $type, $line ) use ( $output ) {
            $output->write( $line );
        } );

        if ( ! $process->isSuccessful() ) {
            throw new ProcessFailedException( $process );
        }
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

}
