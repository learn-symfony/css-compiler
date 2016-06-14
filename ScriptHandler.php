<?php

namespace EM\CssCompiler\Handler;

use Composer\Script\Event;
use EM\CssCompiler\Processor\Processor;

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
            throw new \InvalidArgumentException('The extra.css-compiler setting must be an array of a configuration objects.');
        }

        if (array_keys($configs) !== range(0, count($configs) - 1)) {
            $configs = [$configs];
        }

        $processor = new Processor($event->getIO());

        foreach ($configs as $config) {
            if (!is_array($config)) {
                throw new \InvalidArgumentException('The extra.css-compiler should contain only configuration objects.');
            }

            foreach ($config['input'] as $item => $value) {
                $processor->attachFiles(__DIR__ . "/{$value}", __DIR__ . "/{$config['output']}");
            }

            $processor->processFiles(isset($config['format']) ? $config['format'] : 'compact');
        }

        $processor->saveOutput();
    }
}
