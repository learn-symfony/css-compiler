<?php

namespace EM\CssCompiler\Container;

use EM\CssCompiler\Exception\FileException;

class File
{
    const TYPE_SCSS    = 'scss';
    const TYPE_SASS    = 'sass';
    const TYPE_COMPASS = 'compass';
    const TYPE_LESS    = 'less';
    /**
     * @var string
     */
    private $sourcePath;
    /**
     * @var string
     */
    private $outputPath;
    /**
     * @var string
     */
    private $sourceContent;
    /**
     * @var string
     */
    private $parsedContent;
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $sourcePath
     * @param string $outputPath
     */
    public function __construct($sourcePath, $outputPath)
    {
        $this->setSourcePath($sourcePath);
        $this->outputPath = $outputPath;
    }

    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    /**
     * @param string $path
     *
     * @return File
     */
    public function setSourcePath($path)
    {
        $this->sourcePath = $path;
        $this->detectSourceTypeFromPath($path);

        return $this;
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
     * @return File
     */
    public function setOutputPath($path)
    {
        $this->outputPath = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getSourceContent()
    {
        return $this->sourceContent;
    }

    /**
     * @param string $content
     *
     * @return File
     */
    public function setSourceContent($content)
    {
        $this->sourceContent = $content;

        return $this;
    }

    /**
     * @return File
     * @throws FileException
     */
    public function setSourceContentFromSourcePath() 
    {
        $this->sourceContent = $this->readSourceContentByPath();

        return $this;
    }

    /**
     * @return string
     */
    public function getParsedContent()
    {
        return $this->parsedContent;
    }

    /**
     * @param string $content
     *
     * @return File
     */
    public function setParsedContent($content) 
    {
        $this->parsedContent = $content;

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
     * @return File
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $path
     * 
     * @return void
     */
    private function detectSourceTypeFromPath($path)
    {
        switch (true) {
            case 0 !== preg_match('/^.*\.' . static::TYPE_SCSS . '/', $path):
                $this->type = static::TYPE_SCSS;
                break;
            case 0 !== preg_match('/^.*\.' . static::TYPE_SASS . '/', $path):
                $this->type = static::TYPE_SASS;
                break;
            case 0 !== preg_match('/^.*\.' . static::TYPE_COMPASS . '/', $path):
                $this->type = static::TYPE_COMPASS;
                break;
            case 0 !== preg_match('/^.*\.' . static::TYPE_LESS . '/', $path):
                $this->type = static::TYPE_LESS;
                break;
            default:
                $this->type = 'unknown';
        }
    }

    /**
     * @return string
     * @throws FileException
     */
    private function readSourceContentByPath()
    {
        if (!file_exists($this->getSourcePath())) {
            throw new FileException("file: {$this->sourcePath} doesn't exists");
        }

        return file_get_contents($this->getSourcePath());
    }
}
