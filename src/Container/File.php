<?php

namespace EM\CssCompiler\Container;

//use Symfony\Component\Yaml\Inline;
//use Symfony\Component\Yaml\Parser;
//use Symfony\Component\Yaml\Yaml;

class File
{
    const TYPE_SCSS = 'scss';
    const TYPE_SASS = 'sass';
    const TYPE_LESS = 'less';
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

//    public function __construct(string $sourcePath, string $outputPath, string $sourceContent, string $parsedContent, string $type)
    public function __construct(string $sourcePath)
    {
        $this->setSourcePath($sourcePath);
//        $this->sourcePath = $sourcePath;
//        $this->outputPath = $outputPath;
//        $this->sourceContent = $sourceContent;
//        $this->parsedContent = $parsedContent;
//        $this->type = $type ?: $this->type;
    }

    public function getSourcePath() : string
    {
        return $this->sourcePath;
    }

    public function setSourcePath(string $path) : self
    {
        $this->sourcePath = $path;
        $this->detectSourceTypeFromPath($path);

        return $this;
    }

    public function getOutputPath() : string
    {
        return $this->outputPath;
    }

    public function setOutputPath(string $path) : self
    {
        $this->outputPath = $path;

        return $this;
    }

    public function getSourceContent() : string
    {
        return $this->sourceContent;
    }

    public function setSourceContent(string $content) : self
    {
        $this->sourceContent = $content;

        return $this;
    }

    public function getParsedContent() : string
    {
        return $this->parsedContent;
    }

    public function setParsedContent(string $content) : self
    {
        $this->parsedContent = $content;

        return $this;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setType(string $type) : self
    {
        $this->type = $type;

        return $this;
    }

    private function detectSourceTypeFromPath(string $path)
    {
        switch (true) {
            case 0 !== preg_match('/^.*\.' . static::TYPE_SCSS . '/', $path):
                $this->type = static::TYPE_SCSS;
                break;
            case 0 !== preg_match('/^.*\.' . static::TYPE_SASS . '/', $path):
                $this->type = static::TYPE_SASS;
                break;
            case 0 !== preg_match('/^.*\.' . static::TYPE_LESS . '/', $path):
                $this->type = static::TYPE_LESS;
                break;
            default:
                $this->type = 'unknown';
        }
    }
}
