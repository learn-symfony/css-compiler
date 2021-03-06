<?php

namespace EM\CssCompiler;

use Composer\Script\Event;
use EM\CssCompiler\Processor\Processor;

/**
 * @see   ScriptHandlerTest
 *
 * @since 0.1
 */
class ScriptHandler
{
    const CONFIG_MAIN_KEY          = 'css-compiler';
    const OPTION_KEY_INPUT         = 'input';
    const OPTION_KEY_OUTPUT        = 'output';
    const OPTION_KEY_FORMATTER     = 'format';
    const DEFAULT_OPTION_FORMATTER = 'compact';
    protected static $mandatoryOptions = [
        self::OPTION_KEY_INPUT  => 'array',
        self::OPTION_KEY_OUTPUT => 'string'
    ];

    /**
     * @api
     *
     * @param Event $event
     *
     * @throws \InvalidArgumentException
     */
    public static function generateCSS(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();
        static::validateConfiguration($extra);

        $processor = new Processor($event->getIO());

        foreach ($extra[static::CONFIG_MAIN_KEY] as $options) {
            foreach ($options[static::OPTION_KEY_INPUT] as $inputSource) {
                $processor->attachFiles(
                    static::resolvePath($inputSource, getcwd()),
                    static::resolvePath($options[static::OPTION_KEY_OUTPUT], getcwd())
                );
            }

            $formatter = array_key_exists(static::OPTION_KEY_FORMATTER, $options) ? $options[static::OPTION_KEY_FORMATTER] : static::DEFAULT_OPTION_FORMATTER;
            $processor->processFiles($formatter);
        }
        $processor->saveOutput();
    }

    /**
     * @param string $path
     * @param string $prefix
     *
     * @return string
     */
    protected static function resolvePath($path, $prefix)
    {
        return '/' === substr($path, 0, 1) ? $path : "{$prefix}/{$path}";
    }

    /**
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    protected static function validateConfiguration(array $config)
    {
        if (!array_key_exists(static::CONFIG_MAIN_KEY, $config)) {
            throw new \InvalidArgumentException('compiler should needs to be configured through the extra.css-compiler setting');
        }

        if (!is_array($config[static::CONFIG_MAIN_KEY])) {
            throw new \InvalidArgumentException('the extra.' . static::CONFIG_MAIN_KEY . ' setting must be an array of objects');
        }

        foreach ($config[static::CONFIG_MAIN_KEY] as $index => $options) {
            if (!is_array($options)) {
                throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[$index] should be an array");
            }

            static::validateMandatoryOptions($options, $index);
        }
    }

    /**
     * @param array $options
     * @param int   $index
     *
     * @throws \InvalidArgumentException
     */
    protected static function validateMandatoryOptions(array $options, $index)
    {
        foreach (static::$mandatoryOptions as $optionIndex => $type) {
            if (!array_key_exists($optionIndex, $options)) {
                throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[$index].{$optionIndex} is required!");
            }

            $callable = "is_{$type}";
            if (!$callable($options[$optionIndex])) {
                throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[$index].{$optionIndex} should be {$type}!");
            }
        }
    }
}
