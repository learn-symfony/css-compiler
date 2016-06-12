<?php

namespace EM\CssCompiler\Handler;

use Composer\Script\Event;
use EM\CssCompiler\Processor\Processor;
use Leafo\ScssPhp\Formatter\Compact;

class ScriptHandler
{
    public static function compileCSS(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extras['css-compiler'])) {
            throw new \InvalidArgumentException('The parameter handler needs to be configured through the extra.css-compiler setting.');
        }

        $configs = $extras['css-compiler'];

        if (!is_array($configs)) {
            throw new \InvalidArgumentException('The extra.css-compiler setting must be an array or a configuration object.');
        }

        if (array_keys($configs) !== range(0, count($configs) - 1)) {
            $configs = [$configs];
        }

        $processor = new Processor($event->getIO());
        exec('rm -rf '. __DIR__ .'/var/*');
        foreach ($configs as $config) {
            if (!is_array($config)) {
                throw new \InvalidArgumentException('The extra.css-compiler setting must be an array of configuration objects.');
            }

            $processor->resetFiles();
            foreach ($config['input'] as $item => $value) {
                /**
                    "input": [
                        "src/assets/scss"
                    ],
                 */
                $processor->attachFiles(__DIR__ . "/$value");
            }

//            $formatter = 
            switch ($config['format'] ?? 'compact') {
                case 'compressed':
                case 'crunched':
                case 'expanded':
                case 'nested':
                case 'compact':
                    $formatter = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($config['format'] ?? 'compact');
                    break;
                default:
                    if (!is_array($config)) {
                        throw new \InvalidArgumentException('there\'re avaliable options: if by default: compact');
                    }

                    break;
            }

            $processor->processFiles($formatter);

            $output = $processor->concatOutput();
            $outputPath = __DIR__ . "/var/${config['output']}";
            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
//            if (file_exists($outputPath)) {
//                $outputDir = dirname($outputPath);
                if (!is_dir($dir = dirname($outputDir))) {
//                if (!is_dir($outputDir)) {
                    mkdir($outputDir, 0755, true);
//                    mkdir($outputDir, 0777);
                }
            }
            file_put_contents(__DIR__ .'/var/output.css', $output);
            file_put_contents($outputPath, $output);
        }
    }
}
