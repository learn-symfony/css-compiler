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
            static::getSharedFixturesDirectory() . '/less'             => 1,
            static::getSharedFixturesDirectory() . '/compass'          => 1,
            static::getSharedFixturesDirectory() . '/scss/layout.scss' => 1,
            static::getSharedFixturesDirectory() . '/scss'             => 4,
            static::getSharedFixturesDirectory()                       => 7
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
    public function processFileOnSCSS()
    {
        $this->invokeProcessFileMethod('scss/layout.scss', '');
    }

    /**
     * @see Processor::processFile
     * @test
     */
    public function processFileOnLESS()
    {
        $this->invokeProcessFileMethod('less/print.less', '');
    }

    /**
     * @see Processor::processFile
     * @test
     */
    public function processFileOnCompass()
    {
        $this->invokeProcessFileMethod('compass/compass-integration.scss', '');
    }

    /**
     * @see Processor::processFile
     * @test
     */
    public function processFileOnImports()
    {
        $this->invokeProcessFileMethod('integration/app.scss', '');
    }

    /**
     * @param string $inputPathPostfix
     * @param string $outputPath
     *
     * @throws \EM\CssCompiler\Exception\CompilerException
     */
    private function invokeProcessFileMethod($inputPathPostfix, $outputPath)
    {
        $file = new FileContainer(static::getSharedFixturesDirectory() . "/{$inputPathPostfix}", $outputPath);
        $file->setInputContent(file_get_contents($file->getInputPath()));

        (new Processor($this->io))->processFile($file);

        $this->assertNotEquals($file->getInputContent(), $file->getOutputContent());
    }

    /**
     * @see Processor::processFile
     * @test
     *
     * @expectedException \EM\CssCompiler\Exception\CompilerException
     */
    public function processFileExpectedException()
    {
        $file = new FileContainer(static::getSharedFixturesDirectory() . '/compass', '');
        $file->setInputContent(file_get_contents($file->getInputPath()));
        $file->setType(FileContainer::TYPE_UNKNOWN);

        (new Processor($this->io))->processFile($file);
    }

    /**
     * @see Processor::getFormatterClass
     * @test
     */
    public function getFormatterClassOnCorrect()
    {
        foreach (Processor::$supportedFormatters as $formatter) {
            $expected = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);

            $this->assertEquals(
                $expected,
                $this->invokeMethod(new Processor($this->io), 'getFormatterClass', [$formatter])
            );
        }
    }

    /**
     * @see Processor::getFormatterClass
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function getFormatterClassOnException()
    {
        $this->invokeMethod(new Processor($this->io), 'getFormatterClass', ['not-existing']);
    }

    /**
     * @see Processor::fetchInputContextIntoFile
     * @test
     */
    public function fetchInputContextIntoFileOnSuccess()
    {
        $file = new FileContainer(static::getSharedFixturesDirectory() . '/scss/layout.scss', '');
        $this->invokeMethod(new Processor($this->io), 'fetchInputContextIntoFile', [$file]);

        $this->assertNotNull($file->getInputContent());
    }

    /**
     * @see Processor::fetchInputContextIntoFile
     * @test
     *
     * @expectedException \EM\CssCompiler\Exception\FileException
     */
    public function fetchInputContextIntoFileOnException()
    {
        $this->invokeMethod(new Processor($this->io), 'fetchInputContextIntoFile', [new FileContainer('input', 'output')]);
    }
}
