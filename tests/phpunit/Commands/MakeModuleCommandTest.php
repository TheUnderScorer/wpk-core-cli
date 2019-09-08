<?php

namespace UnderScorer\CoreCli\Tests\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use UnderScorer\CoreCli\Commands\MakeModuleCommand;
use UnderScorer\CoreCli\Filesystem\Filesystem;
use UnderScorer\CoreCli\Filesystem\Path;
use UnderScorer\CoreCli\Tests\TestCase;

/**
 * Class MakeModuleCommandTest
 * @package UnderScorer\CoreCli\Tests\Commands
 */
final class MakeModuleCommandTest extends TestCase
{

    /**
     * @var Filesystem|MockObject
     */
    private $fsMock;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @return void
     */
    public function testShouldFailIfModuleNameContainsModuleKeyword(): void
    {
        $this->expectException( RuntimeException::class );
        $this->expectExceptionMessage( 'Error! Module name cannot contain "module" keyword!' );

        $this->commandTester->execute( [
            MakeModuleCommand::MODULE_NAME => 'testmodule',
        ] );
    }

    /**
     * @return void
     */
    public function testExecuteWithExistentConfig(): void
    {
        $expectedModulePath     = Path::join( self::getRootDirDotted(), 'app.Modules.Test', '.' );
        $expectedModuleFilePath = $expectedModulePath . 'TestModule.php';
        $configModulePath       = Path::join( self::getRootDir(), 'config.' ) . 'modules.php';

        $expectedModuleConfigFile = <<<EOL
            <?php
            
            return [
                'some' => 'UnderScorer\Modules\Some\SomeModule',
                'test' => 'UnderScorer\Modules\Test\TestModule',
            ];
            EOL;

        $expectedModule = <<<EOL
        <?php
        
        namespace UnderScorer\Modules\Test;
        
        use UnderScorer\Core\Module;
        
        /**
         * Class TestModule
         * @package UnderScorer\Modules\Test
         */
        class TestModule extends Module
        {
        
            /**
             * Performs module bootstrap
             *
             * @return void
             */
            protected function bootstrap(): void
            {
        
            }
        
        }
        EOL;

        $this->fsMock
            ->expects( $this->exactly( 2 ) )
            ->method( 'dumpFile' )
            ->withConsecutive(
                [ $expectedModuleFilePath, trim( $expectedModule ) ],
                [ $configModulePath, trim( $expectedModuleConfigFile ) ]
            );

        $this->fsMock
            ->expects( $this->once() )
            ->method( 'exists' )
            ->willReturn( true )
            ->with( $configModulePath );

        $this->fsMock
            ->expects( $this->once() )
            ->method( 'require' )
            ->with( $configModulePath )
            ->willReturn( [
                'some' => 'UnderScorer\Modules\Some\SomeModule',
            ] );
        $this->commandTester->execute( [
            MakeModuleCommand::MODULE_NAME => 'Test',
        ] );
    }

    /**
     * @return void
     */
    public function testExecuteWithNonExistentConfig(): void
    {
        $expectedModulePath           = Path::join( self::getRootDirDotted(), 'app.Modules.Test', '.' );
        $expectedModuleFilePath       = $expectedModulePath . 'TestModule.php';
        $expectedModuleConfigFilePath = Path::join( self::getRootDir(), 'config.' ) . 'modules.php';

        $expectedModuleConfigFile = <<<EOL
            <?php
            
            return [
                'test' => 'UnderScorer\Modules\Test\TestModule',
            ];
            EOL;

        $expectedModule = <<<EOL
        <?php
        
        namespace UnderScorer\Modules\Test;
        
        use UnderScorer\Core\Module;
        
        /**
         * Class TestModule
         * @package UnderScorer\Modules\Test
         */
        class TestModule extends Module
        {
        
            /**
             * Performs module bootstrap
             *
             * @return void
             */
            protected function bootstrap(): void
            {
        
            }
        
        }
        EOL;

        $this->fsMock
            ->expects( $this->exactly( 2 ) )
            ->method( 'dumpFile' )
            ->withConsecutive(
                [ $expectedModuleFilePath, trim( $expectedModule ) ],
                [ $expectedModuleConfigFilePath, trim( $expectedModuleConfigFile ) ],
                );

        $this->fsMock
            ->expects( $this->at( 4 ) )
            ->method( 'exists' )
            ->willReturn( false )
            ->with( $expectedModuleConfigFilePath );

        $this->commandTester->execute( [
            MakeModuleCommand::MODULE_NAME => 'Test',
        ] );
    }

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->fsMock = $this
            ->getMockBuilder( Filesystem::class )
            ->onlyMethods( [
                'exists',
                'mkdir',
                'mirror',
                'copy',
                'touch',
                'tempnam',
                'dumpFile',
                'require',
                'getContents',
                'getCwd',
            ] )
            ->disableOriginalConstructor()
            ->getMock();

        $this->fsMock
            ->method( 'getCwd' )
            ->willReturn( $this->getRootDir() );

        $container->singleton( Filesystem::class, function () {
            return $this->fsMock;
        } );

        $app = new Application();
        $app->add(
            $container->make( MakeModuleCommand::class, [ 'rootDir' => self::getRootDir() ] )
        );

        $command             = $app->find( MakeModuleCommand::getDefaultName() );
        $this->commandTester = new CommandTester( $command );
    }

}
