<?php

namespace EM\CssCompiler\Processor;

use Composer\IO\IOInterface;
use EM\CssCompiler\Container\File;
use EM\CssCompiler\Exception\CompilerException;
use Leafo\ScssPhp\Compiler as SASSCompiler;
use lessc as LESSCompiler;
use scss_compass as CompassCompiler;

/**
 * @since 0.1
 */
class Processor
{
    const FORMATTER_COMPRESSED = 'compressed';
    const FORMATTER_CRUNCHED   = 'crunched';
    const FORMATTER_EXPANDED   = 'expanded';
    const FORMATTER_NESTED     = 'nested';
    const FORMATTER_COMPACT    = 'compact';
    const SUPPORTED_FORMATTERS = [
        self::FORMATTER_COMPRESSED,
        self::FORMATTER_CRUNCHED,
        self::FORMATTER_EXPANDED,
        self::FORMATTER_NESTED,
        self::FORMATTER_COMPACT
    ];
    /**
     * @var IOInterface
     */
    private $io;
    /**
     * @var File[]
     */
    private $files = [];
    /**
     * @var SASSCompiler
     */
    private $sass;
    /**
     * @var LESSCompiler
     */
    private $less;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->initCompilers();
    }

    protected function initCompilers()
    {
        $this->less = new LESSCompiler();
        $this->sass = new SASSCompiler();
        /** attaches compass functionality to the SASS compiler */
        new CompassCompiler($this->sass);
    }

    /**
     * @param string $inputPath
     * @param string $outputPath
     *
     * @throws \Exception
     */
    public function attachFiles($inputPath, $outputPath)
    {
        if (is_dir($inputPath)) {
            $files = scandir($inputPath);
            unset($files[0], $files[1]);

            foreach ($files as $file) {
                $this->attachFiles("$inputPath/$file", $outputPath);
            }
        } else if (is_file($inputPath)) {
            $this->files[] = new File($inputPath, $outputPath);
        } else {
            throw new \Exception("file doesn't exists");
        }
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string[]
     */
    protected function concatOutput()
    {
        $outputMap = [];
        foreach ($this->files as $file) {
            if (!isset($outputMap[$file->getOutputPath()])) {
                $outputMap[$file->getOutputPath()] = '';
            }

            $outputMap[$file->getOutputPath()] .= $file->getParsedContent();
        }

        return $outputMap;
    }

    /**
     * save output into file
     */
    public function saveOutput()
    {
        foreach ($this->concatOutput() as $path => $content) {
            $directory = dirname($path);
            if (!is_dir($directory)) {
                $this->io->write("<info>creating directory</info>: {$directory}");
                mkdir($directory, 0755, true);
            }

            $this->io->write("<info>save output into</info>: {$path}");
            file_put_contents($path, $content);
        }
    }

    /**
     * @param string $formatter
     *
     * @throws CompilerException
     */
    public function processFiles($formatter)
    {
        $this->sass->setFormatter($this->getFormatterClass($formatter));
        $this->io->write("<info>use '{$formatter}' formatting</info>");

        foreach ($this->files as $file) {
            $this->io->write("<info>processing</info>: {$file->getSourcePath()}");
            $file->setSourceContentFromSourcePath();

            try {
                $this->processFile($file);
            } catch (CompilerException $e) {
                $this->io->writeError("<error>failed to process: {$file->getSourcePath()}</error>");
            }
        }
    }

    /**
     * @param File $file
     *
     * @return File
     * @throws CompilerException
     */
    public function processFile(File $file)
    {
        switch ($file->getType()) {
            case File::TYPE_COMPASS:
            case File::TYPE_SCSS:
            case File::TYPE_SASS:
                return $file->setParsedContent($this->sass->compile($file->getSourceContent()));
            case File::TYPE_LESS:
                return $file->setParsedContent($this->less->compile($file->getSourceContent()));
        }

        throw new CompilerException('unknown compiler');
    }

    /**
     * @param string $formatter
     *
     * @return string
     */
    protected function getFormatterClass($formatter)
    {
        if (!in_array($formatter, static::SUPPORTED_FORMATTERS)) {
            throw new \InvalidArgumentException('unknown formatter, available options are: ' . print_r(static::SUPPORTED_FORMATTERS, true));
        }

        return 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);
    }
}
