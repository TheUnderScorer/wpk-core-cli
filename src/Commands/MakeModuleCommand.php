<?php

namespace UnderScorer\CoreCli\Commands;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName( 'module:create' )
            ->setDescription( 'Creates module' )
            ->addArgument(
                self::MODULE_NAME,
                InputArgument::REQUIRED,
                'Name of created module.',
                ''
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $moduleName = $input->getArgument( self::MODULE_NAME );

        if ( strpos( $moduleName, 'module' ) !== false ) {
            throw new RuntimeException( 'Error! Module name cannot contain "module" keyword!' );
        }
    }

}
