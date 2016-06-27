<?php

namespace EM\CssCompiler\Tests\PHPUnit\Processor;

use Composer\IO\IOInterface;
use EM\CssCompiler\Container\FileContainer;
use EM\CssCompiler\Processor\Processor;
use EM\CssCompiler\Tests\Environment\IntegrationTestSuite;

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
            static::getSharedFixturesDirectory() . '/sass'             => 1,
            static::getSharedFixturesDirectory() . '/compass'          => 1,
            static::getSharedFixturesDirectory() . '/scss'             => 3,
            static::getSharedFixturesDirectory() . '/scss/layout.scss' => 1
        ];
        foreach ($paths as $path => $expectedFiles) {
            $processor = new Processor($this->io);
            $processor->attachFiles($path, '');

            $this->assertCount($expectedFiles, $processor->getFiles());
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
        (new Processor($this->io))->attachFiles(static::getSharedFixturesDirectory() . '/do-not-exists', '');
    }

    /**
     * @see Processor::processFile
     * @test
     */
    public function processFileSASS()
    {
        $file = (new FileContainer(static::getSharedFixturesDirectory() . '/scss/layout.scss', ''))
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
        $file = (new FileContainer(static::getSharedFixturesDirectory() . '/compass', ''))
            ->setSourceContentFromSourcePath()
            ->setType(FileContainer::TYPE_UNKNOWN);

        (new Processor($this->io))->processFile($file);
    }

    /**
     * @see Processor::getFormatterClass
     * @test
     */
    public function getFormatterClass()
    {
        foreach (Processor::$supportedFormatters as $formatter) {
            $expected = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);

            $this->assertEquals(
                $expected,
                $this->invokeMethod(new Processor($this->io), 'getFormatterClass', [$formatter])
            );
        }
    }
}
