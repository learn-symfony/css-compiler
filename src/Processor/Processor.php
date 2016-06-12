<?php

namespace EM\CssCompiler\Processor;

use Composer\IO\IOInterface;
use EM\CssCompiler\Container\File;
use Leafo\ScssPhp\Compiler;

class Processor
{
    const TYPE_SCSS = 'scss';
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
//    public function __construct(IOInterface $io)
//    {
//        $this->io = $io;
//    }

    public function resetFiles()
    {
        $this->files = [];
    }

    public function attachFiles(string $path)
    {
        if (is_dir($path)) {
            $files = scandir($path);
            unset($files[0], $files[1]);

            foreach ($files as $file) {
                $absolutePath = "$path/$file";
                if (is_file($absolutePath)) {
                    $this->files[] = new File($absolutePath);
                } else {
                    $this->attachFiles($absolutePath);
                }
            }
        } else {
            $this->files[] = new File($path);
        }
    }

    public function concatOutput() : string
    {
        $output = '';
        foreach ($this->files as $file) {
            $output .= $file->getParsedContent();
        }

        return $output;
    }

    public function processFiles(string $formatter)
    {
        $lessCompiler = new \lessc();
//        $sassCompiler = new Compiler();
//        $compass = new \scss_compass($sassCompiler);
        $sassCompiler = new Compiler();
        $sassCompiler->setFormatter($formatter);
        new \scss_compass($sassCompiler);

        foreach ($this->files as $file) {
            $content = file_get_contents($file->getSourcePath());
            $file->setSourceContent($content);

            switch ($file->getType()) {
                case static::TYPE_SCSS:
                    $content = $sassCompiler->compile($content);
                    break;
                case static::TYPE_SASS:
                    $content = $sassCompiler->compile($content);
                    break;
                case static::TYPE_LESS:
                    $content = $lessCompiler->compile($content);
                    break;
            }
            $file->setParsedContent($content);
        }
    }
}
