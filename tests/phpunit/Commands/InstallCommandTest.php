<?php

namespace UnderScorer\CoreCli\Tests\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use UnderScorer\CoreCli\Commands\InstallCommand;
use UnderScorer\CoreCli\Filesystem\Path;
use UnderScorer\CoreCli\Tests\TestCase;

/**
 * Class InstallCommandTest
 * @package UnderScorer\CoreCli\Tests\Commands
 */
final class InstallCommandTest extends TestCase
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
     * @var int
     */
    private $processCalls = 0;

    /**
     * @return void
     */
    public function testExecuteWithDefaults(): void
    {
        $expectedTargetDir = Path::join( getcwd(), 'wpk-core' );

        $this->bindProcessIntoContainer( $expectedTargetDir );

        $this->commandTester->execute( [] );
    }

    /**
     * @param string $expectedTargetDir
     */
    private function bindProcessIntoContainer( string $expectedTargetDir ): void
    {
        self::getContainer()
            ->bindIf( Process::class, function () use ( $expectedTargetDir ) {
                $args            = func_get_args();
                $constructorArgs = $args[ 1 ];

                $mockProcess = $this
                    ->getMockBuilder( Process::class )
                    ->onlyMethods( [ 'run', 'isSuccessful' ] )
                    ->disableOriginalConstructor()
                    ->getMock();

                $mockProcess->method( 'run' )->willReturn( 0 );
                $mockProcess->method( 'isSuccessful' )->willReturn( true );

                switch ( $this->processCalls ) {
                    case 0:
                        $this->assertEquals( 'composer install --prefer-dist --no-interaction', $constructorArgs[ 'command' ] );
                        $this->assertEquals( $expectedTargetDir, $constructorArgs[ 'cwd' ] );
                        break;

                    case 1:
                        $this->assertEquals( 'npm install', $constructorArgs[ 'command' ] );
                        $this->assertEquals( $expectedTargetDir, $constructorArgs[ 'cwd' ] );
                        break;

                    default:
                        $this->fail( 'Invalid self::$processCalls value.' );
                }

                $this->processCalls ++;

                return $mockProcess;
            } );

        $this
            ->fsMock
            ->expects( $this->once() )
            ->method( 'exists' )
            ->with( $expectedTargetDir );

        $this
            ->fsMock
            ->expects( $this->once() )
            ->method( 'mkdir' )
            ->with( $expectedTargetDir );

        $this
            ->fsMock
            ->expects( $this->once() )
            ->method( 'mirror' )
            ->with( Path::join( self::getRootDir(), 'files.plugin' ), $expectedTargetDir );
    }

    /**
     * @return void
     */
    public function testExecuteWithCustomDirectory(): void
    {
        $expectedTargetDir = Path::join( getcwd(), 'test' );

        $this->bindProcessIntoContainer( $expectedTargetDir );

        $this->commandTester->execute( [
            InstallCommand::PLUGIN_DIR => 'test',
        ] );
    }

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->fsMock = $this
            ->getMockBuilder( Filesystem::class )
            ->onlyMethods( [ 'exists', 'mkdir', 'mirror' ] )
            ->disableOriginalConstructor()
            ->getMock();

        $container->singleton( Filesystem::class, function () {
            return $this->fsMock;
        } );

        $app = new Application();
        $app->add(
            $container->make( InstallCommand::class, [ 'rootDir' => self::getRootDir() ] )
        );

        $command             = $app->find( InstallCommand::getDefaultName() );
        $this->commandTester = new CommandTester( $command );
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->processCalls = 0;

        $container = self::getContainer();

        unset( $container[ Filesystem::class ] );
        unset( $container[ Process::class ] );
    }

}
