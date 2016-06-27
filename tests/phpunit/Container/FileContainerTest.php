<?php

namespace EM\CssCompiler\Tests\Container;

use EM\CssCompiler\Container\FileContainer;
use EM\CssCompiler\Tests\Environment\IntegrationTestSuite;

/**
 * @see FileContainer
 */
class FileContainerTest extends IntegrationTestSuite
{
    /**
     * @see FileContainer::__constuct
     * @see FileContainer::TYPE_UNKNOWN
     *
     * @test
     */
    public function constructOnUnknownType()
    {
        $this->invokeConstructor('input', 'output', FileContainer::TYPE_UNKNOWN);
    }

    /**
     * @see FileContainer::__constuct
     * @see FileContainer::TYPE_SCSS
     *
     * @test
     */
    public function constructOnSCSSType()
    {
        $this->invokeConstructor('input.scss', 'output', FileContainer::TYPE_SCSS);
    }

    /**
     * @see FileContainer::__constuct
     * @see FileContainer::TYPE_LESS
     *
     * @test
     */
    public function constructOnLESSType()
    {
        $this->invokeConstructor('input.less', 'output', FileContainer::TYPE_LESS);
    }

    /**
     * as FileContainer can't exists without (in|out)put need to check that:
     *  (in|out)put paths assigned successfully
     *  (in|out)content is null
     *  type should not be null and be detected using @see FileContainer::detectInputTypeByInputPath
     *
     * @param string $inputPath
     * @param string $outputPath
     * @param string $expectedType
     */
    private function invokeConstructor($inputPath, $outputPath, $expectedType)
    {
        $file = new FileContainer($inputPath, $outputPath);

        $this->assertEquals($inputPath, $file->getInputPath());
        $this->assertEquals($outputPath, $file->getOutputPath());

        $this->assertNull($file->getOutputContent());
        $this->assertNull($file->getInputContent());

        $this->assertNotNull($file->getType());
        $this->assertEquals($expectedType, $file->getType());
    }

    /**
     * @see FileContainer::detectInputTypeByInputPath
     * @test
     */
    public function detectInputTypeByInputPath()
    {
        $inputPaths = [
            'input.css'     => FileContainer::TYPE_UNKNOWN,
            'input'         => FileContainer::TYPE_UNKNOWN,
            'input.sass'    => FileContainer::TYPE_UNKNOWN,
            'input.compass' => FileContainer::TYPE_UNKNOWN,
            'input.scss'    => FileContainer::TYPE_SCSS,
            'input.less'    => FileContainer::TYPE_LESS
        ];

        foreach ($inputPaths as $inputPath => $expectedType) {
            $file = new FileContainer($inputPath, '');
            $this->assertEquals($expectedType, $file->getType());
        }
    }
}
