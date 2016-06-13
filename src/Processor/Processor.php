<?php

namespace EM\CssCompiler\Processor;

use Composer\IO\IOInterface;
use EM\CssCompiler\Container\File;
use EM\CssCompiler\Exception\CompilerException;
use Leafo\ScssPhp\Compiler as SASSCompiler;
use lessc as LESSCompiler;
use scss_compass as CompassCompiler;

class Processor
{
    const TYPE_SCSS = 'scss';
    const TYPE_COMPASS = 'scss';
    const TYPE_SASS = 'sass';
    const TYPE_LESS = 'less';
    /**
     * @var IOInterface
     */
    private $io;
    /**
     * @var File[]
     */
    private $files = [];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function resetFiles()
    {
        $this->files = [];
    }

    public function attachFiles(string $inputPath, string $outputPath)
    {
        if (is_dir($inputPath)) {
            $files = scandir($inputPath);
            unset($files[0], $files[1]);

            foreach ($files as $file) {
                $absolutePath = "$inputPath/$file";
                if (is_file($absolutePath)) {
                    $this->files[] = new File($absolutePath, $outputPath);
                } else {
                    $this->attachFiles($absolutePath, $outputPath);
                }
            }
        } else if (is_file($inputPath)) {
            $this->files[] = new File($inputPath, $outputPath);
        } else {
            throw new \Exception('file doesn\'t exists');
        }
    }

    public function concatOutput() : array
    {
        $outputMap = [];
        foreach ($this->files as $file) {
            if (!isset($outputMap[$file->getOutputPath()])) {
                $outputMap[$file->getOutputPath()] = $file->getParsedContent();
            } else {
                $outputMap[$file->getOutputPath()] .= $file->getParsedContent();
            }
        }

        return $outputMap;
    }

    public function saveOutput()
    {
        foreach ($this->concatOutput() as $path => $content) {

            $directory = dirname($path);
            if (!is_dir($dir = $directory)) {
                $this->io->write("creating directory: {$directory}");
                mkdir($directory, 0755, true);
            }

            $this->io->write("creating output: {$path}");
            file_put_contents($path, $content);
        }
    }

    public function processFiles(string $formatter)
    {
        switch ($formatter) {
            case 'compressed':
            case 'crunched':
            case 'expanded':
            case 'nested':
            case 'compact':
                $formatter = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);
                break;
            default:
                throw new \InvalidArgumentException('available options are: xxx');
        }
//        -f=format   Set the output format, includes "default", "compressed"

//        switch ($formatter) {
//            case 'compressed':
//            case 'crunched':
//            case 'expanded':
//            case 'nested':
//            case 'compact':
//                $formatter = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($formatter);
//                break;
//            default:
//                throw new \InvalidArgumentException('available options are: xxx');
//        }

        $lessCompiler = new LESSCompiler();
//        $lessCompiler->setFormatter()
        $sassCompiler = new SASSCompiler();
        $sassCompiler->setFormatter($formatter);

        /** attaches compass functionality to the SASS compiler */
        new CompassCompiler($sassCompiler);

        foreach ($this->files as $file) {
            $this->io->write("processing file: {$file->getSourcePath()}");
            $file->setSourceContentFromSourcePath();

            switch ($file->getType()) {
                case static::TYPE_SCSS:
                    $content = $sassCompiler->compile($file->getSourceContent());
                    break;
                case static::TYPE_SASS:
                    $content = $sassCompiler->compile($file->getSourceContent());
                    break;
                case static::TYPE_LESS:
                    $content = $lessCompiler->compile($file->getSourceContent());
                    break;
                default:
                    throw new CompilerException('unknown compiler');
            }

            $file->setParsedContent($content);
        }
    }
}
