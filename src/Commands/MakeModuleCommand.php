<?php

namespace UnderScorer\CoreCli\Commands;

use Exception;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnderScorer\CoreCli\Filesystem\Path;
use UnderScorer\CoreCli\Namespaces\Resolver;

/**
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

        $baseNamespace = Resolver::getBaseNamespace( Path::join( $this->getRootDir(), '.' ) );
        $namespace     = $baseNamespace . '\\' . $this->modulesNamespace . '\\' . $moduleName;

        $output->writeln( "Resolved namespace: $namespace" );

        $moduleFile = self::setupModuleFile( $moduleTemplate, $moduleName, $namespace );

        $output->writeln( 'Creating module file...' );

        $this->createModuleDirectory( $moduleName, $moduleFile );

        $output->writeln( "<comment>Module $moduleName created!</comment>" );
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

}
