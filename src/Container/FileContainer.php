<?php

namespace EM\CssCompiler\Container;

/**
 * @see   FileContainerTest
 *
 * @since 0.1
 */
class FileContainer
{
    const TYPE_UNKNOWN = 'unknown';
    const TYPE_SCSS    = 'scss';
    const TYPE_LESS    = 'less';
    static $supportedTypes = [
        self::TYPE_SCSS,
        self::TYPE_LESS
    ];
    /**
     * @var string
     */
    private $inputPath;
    /**
     * @var string
     */
    private $outputPath;
    /**
     * @var string
     */
    private $inputContent;
    /**
     * @var string
     */
    private $outputContent;
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $inputPath
     * @param string $outputPath
     */
    public function __construct($inputPath, $outputPath)
    {
        $this
            ->setInputPath($inputPath)
            ->setOutputPath($outputPath);
    }

    /**
     * @return string
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setOutputPath($path)
    {
        $this->outputPath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getInputContent()
    {
        return $this->inputContent;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setInputContent($content)
    {
        $this->inputContent = $content;

        return $this;
    }

    public function getInputPath()
    {
        return $this->inputPath;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setInputPath($path)
    {
        $this->inputPath = $path;
        $this->detectInputTypeByInputPath();

        return $this;
    }

    /**
     * @return string
     */
    public function getOutputContent()
    {
        return $this->outputContent;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setOutputContent($content)
    {
        $this->outputContent = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    protected function detectInputTypeByInputPath()
    {
        $extension = strtolower(pathinfo($this->getInputPath(), PATHINFO_EXTENSION));

        $type = in_array($extension, static::$supportedTypes)
            ? $extension
            : static::TYPE_UNKNOWN;

        $this->setType($type);

        return $this;
    }
}
