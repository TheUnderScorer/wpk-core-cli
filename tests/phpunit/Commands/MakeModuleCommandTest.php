<?php

namespace UnderScorer\CoreCli\Tests\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use UnderScorer\CoreCli\Commands\MakeModuleCommand;
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
    public function testExecute(): void
    {
        $expectedModulePath     = Path::join( self::getRootDirDotted(), 'app.Modules.Test', '.' );
        $expectedModuleFilePath = $expectedModulePath . 'TestModule.php';

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
            ->expects( $this->once() )
            ->method( 'dumpFile' )
            ->with( $expectedModuleFilePath, trim( $expectedModule ) );

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
            ->onlyMethods( [ 'exists', 'mkdir', 'mirror', 'copy', 'touch', 'tempnam', 'dumpFile' ] )
            ->disableOriginalConstructor()
            ->getMock();

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
