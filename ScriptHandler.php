<?php

namespace EM\CssCompiler\Handler;

use Composer\Script\Event;
use EM\CssCompiler\Processor\Processor;

class ScriptHandler
{
    const CONFIG_MAIN_KEY          = 'css-compiler';
    const CONFIG_INPUT_KEY         = 'input';
    const CONFIG_OUTPUT_KEY        = 'output';
    const CONFIG_FORMATTER_KEY     = 'format';
    const CONFIG_DEFAULT_FORMATTER = 'compact';

    public static function compileCSS(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        static::validateConfiguration($extra);

        $processor = new Processor($event->getIO());
        $currentDirectory = getcwd();
        foreach ($extra[static::CONFIG_MAIN_KEY] as $config) {
            foreach ($config[static::CONFIG_INPUT_KEY] as $item => $value) {
                $processor->attachFiles("{$currentDirectory}/{$value}", "{$currentDirectory}/{$config[static::CONFIG_OUTPUT_KEY]}");
            }

            $formatter = isset($config[static::CONFIG_FORMATTER_KEY])
                ? $config[static::CONFIG_FORMATTER_KEY]
                : static::CONFIG_DEFAULT_FORMATTER;

            $processor->processFiles($formatter);
        }

        $processor->saveOutput();
    }

    protected static function validateConfiguration(array $config)
    {
        if (!isset($config[static::CONFIG_MAIN_KEY])) {
            throw new \InvalidArgumentException('the parameter handler needs to be configured through the extra.css-compiler setting.');
        }

        if (!is_array($config[static::CONFIG_MAIN_KEY])) {
            throw new \InvalidArgumentException('the extra.css-compiler setting must be an array of a configuration objects.');
        }

        foreach ($config[static::CONFIG_MAIN_KEY] as $el) {
            if (!is_array($el)) {
                throw new \InvalidArgumentException('The extra.css-compiler should contain only configuration objects.');
            }

            if (!isset($el[static::CONFIG_INPUT_KEY])) {
                throw new \InvalidArgumentException('The extra.css-compiler[].' . static::CONFIG_INPUT_KEY . ' is required!');
            }
            if (!isset($el[static::CONFIG_OUTPUT_KEY])) {
                throw new \InvalidArgumentException('The extra.css-compiler[].' . static::CONFIG_OUTPUT_KEY . ' is required!');
            }
        }
    }
}
