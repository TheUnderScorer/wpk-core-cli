<?php

namespace UnderScorer\CoreCli\Commands;

use Exception;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnderScorer\CoreCli\Filesystem\Path;
use UnderScorer\CoreCli\Namespaces\Resolver;
use UnderScorer\CoreCli\Output\Exports;

/**
 * TODO Add created module into config/modules.php
 *
 * Class MakeModuleCommand
 * @package UnderScorer\CoreCli\Commands
 */
final class MakeModuleCommand extends BaseCommand
{

    /**
     * @var string
     */
    const MODULE_NAME = 'module_name';

    /**
     * @var string
     */
    protected static $defaultName = 'module:create';

    /**
     * @var string Stores currently used module file
     */
    private $moduleFile = '';

    /**
     * @var string
     */
    private $modulesNamespace = 'Modules';

    /**
     * @return string
     */
    public function getModuleFile(): string
    {
        return $this->moduleFile;
    }

    /**
     * @param string $moduleFile
     *
     * @return self
     */
    public function setModuleFile( string $moduleFile ): self
    {
        $this->moduleFile = $moduleFile;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setDescription( 'Creates module' )
            ->addArgument(
                self::MODULE_NAME,
                InputArgument::REQUIRED,
                'Name of created module.',
                );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws Exception
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $moduleName      = $input->getArgument( self::MODULE_NAME );
        $lowerModuleName = strtolower( $moduleName );

        if ( strpos( $lowerModuleName, 'module' ) !== false ) {
            throw new RuntimeException( 'Error! Module name cannot contain "module" keyword!' );
        }

        $moduleTemplate = $this->getModuleTemplate();

        $output->writeln( 'Resolving application base namespace...' );

        $baseNamespace   = Resolver::getBaseNamespace( Path::join( $this->getRootDir(), '.' ) );
        $moduleNamespace = $baseNamespace . '\\' . $this->modulesNamespace . '\\' . $moduleName;
        $fullClassName   = $moduleNamespace . '\\' . $moduleName . 'Module';
        $output->writeln( "Resolved namespace: $moduleNamespace" );

        $moduleFile = self::setupModuleFile( $moduleTemplate, $moduleName, $moduleNamespace );

        $output->writeln( 'Creating module file...' );

        $this->createModuleDirectory( $moduleName, $moduleFile );

        $output->writeln( "<comment>Module $moduleName created!</comment>" );
        $output->writeln( 'Adding module into modules.php ...' );

        $this->appendToModules( $fullClassName, $moduleName );

        $output->writeln( '<comment>Module added into your modules config. All done!</comment>' );
    }

    /**
     * @return string
     */
    private function getModuleTemplate(): string
    {
        $filePath = Path::join( $this->getRootDir(), 'files.module.' );
        $content  = @file_get_contents( $filePath . 'Module.txt' );

        if ( empty( $content ) ) {
            throw new RuntimeException( 'Unable to read template module file.' );
        }

        return $content;
    }

    /**
     * @param string $template
     * @param string $moduleClassName
     * @param string $namespace
     *
     * @return string
     */
    private static function setupModuleFile( string $template, string $moduleClassName, string $namespace ): string
    {
        return str_replace(
            [
                '%%NAMESPACE%%',
                '%%MODULE_NAME%%',
            ],
            [
                $namespace,
                $moduleClassName . 'Module',
            ],
            $template
        );
    }

    /**
     * @param string $moduleName
     * @param string $moduleFileContent
     */
    private function createModuleDirectory( string $moduleName, string $moduleFileContent ): void
    {
        $moduleDirectory = Path::join( $this->getRootDir(), 'app', $this->modulesNamespace, $moduleName, '.' );
        $moduleFilePath  = $moduleDirectory . $moduleName . 'Module.php';

        $this->getFilesystem()->dumpFile( $moduleFilePath, trim( $moduleFileContent ) );
    }

    /**
     * Appends module into modules config
     *
     * @param string $moduleClass
     * @param string $moduleName
     *
     * @return void
     */
    private function appendToModules( string $moduleClass, string $moduleName ): void
    {
        $moduleID = lcfirst( $moduleName );

        $modulesConfigFilePath = Path::join( $this->getRootDir(), 'config.' ) . 'modules.php';

        if ( ! $this->getFilesystem()->exists( $modulesConfigFilePath ) ) {
            $modules = <<<EOL
            <?php
            
            return [
                '$moduleID' => '$moduleClass',
            ];
            EOL;

            $this->getFilesystem()->dumpFile( $modulesConfigFilePath, trim( $modules ) );

            return;
        }

        $configArr = $this->getFilesystem()->require( $modulesConfigFilePath );

        if ( ! is_array( $configArr ) ) {
            throw new RuntimeException( 'Invalid `modules.php` config received, make sure that it is an file that returns an array of modules IDs and their class references.' );
        }

        $configArr[ $moduleID ] = $moduleClass;

        $configArrAsString     = Exports::varExport( $configArr, true );
        $configArrFixedSlashes = str_replace( '\\\\', '\\', $configArrAsString );

        $configFileContent = <<<EOL
        <?php
        
        return $configArrFixedSlashes;
        EOL;

        $this->getFilesystem()->dumpFile( $modulesConfigFilePath, trim( $configFileContent ) );
    }

}
