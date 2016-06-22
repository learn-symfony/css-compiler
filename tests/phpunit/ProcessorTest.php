<?php

namespace EM\Tests\PHPUnit;

use Composer\IO\IOInterface;
use EM\CssCompiler\Container\File;
use EM\CssCompiler\Processor\Processor;
use EM\Tests\Environment\IntegrationTestSuite;

/**
 * @see Processor
 */
class ProcessorTest extends IntegrationTestSuite
{
    protected $event;
    protected $io;
    protected $package;

    protected function setUp()
    {
        $this->io = $this->getMockBuilder(IOInterface::class)->getMock();
    }

    /**
     * @see Processor::attachFiles
     * @test
     */
    public function attachFiles()
    {
        $paths = [
            static::getSharedFixturesDirectory() . '/sass',
            static::getSharedFixturesDirectory() . '/compass'
        ];
        $cacheDir = dirname(dirname(__DIR__)) . '/var/cache';

        foreach ($paths as $path) {
            $processor = new Processor($this->io);
            $processor->attachFiles($path, $cacheDir);

            $this->assertCount(2, $processor->getFiles());
        }
    }

    /**
     * @see Processor::attachFiles
     * @test
     *
     * @expectedException \Exception
     */
    public function attachFilesExpectedException()
    {
        $path = static::getSharedFixturesDirectory() . '/do-not-exists';
        $cacheDir = dirname(dirname(__DIR__)) . '/var/cache';

        $processor = new Processor($this->io);
        $processor->attachFiles($path, $cacheDir);

        $this->assertCount(2, $processor->getFiles());
    }

    /**
     * @see Processor::processFile
     * @test
     */
    public function processFileSASS()
    {
        $file = (new File(static::getSharedFixturesDirectory() . '/compass/sass/layout.scss', ''))
            ->setSourceContentFromSourcePath();

        (new Processor($this->io))->processFile($file);

        $this->assertNotEquals($file->getParsedContent(), $file->getSourceContent());
    }

    /**
     * @see Processor::processFile
     * @test
     *
     * @expectedException \EM\CssCompiler\Exception\CompilerException
     */
    public function processFileExpectedException()
    {
        $file = (new File(static::getSharedFixturesDirectory() . '/compass/sass/', ''))
            ->setSourceContentFromSourcePath()
            ->setType(File::TYPE_UNKNOWN);

        (new Processor($this->io))->processFile($file);
    }

    /**
     * @see Processor::getFormatterClass
     * @test
     */
    public function getFormatterClass()
    {
        foreach (Processor::SUPPORTED_FORMATTERS as $formatter) {
            $expected = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);

            $this->assertEquals(
                $expected,
                $this->invokeMethod(new Processor($this->io), 'getFormatterClass', [$formatter])
            );
        }
    }
}
