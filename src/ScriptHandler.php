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
        self::OPTION_KEY_INPUT,
        self::OPTION_KEY_OUTPUT
    ];

    /**
     * @param Event $event
     *
     * @throws \InvalidArgumentException
     */
    public static function generateCSS(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();
        static::validateConfiguration($extra);

        $processor = new Processor($event->getIO());

        foreach ($extra[static::CONFIG_MAIN_KEY] as $config) {
            foreach ($config[static::OPTION_KEY_INPUT] as $inputSource) {
                $processor->attachFiles(
                    static::resolvePath($inputSource, getcwd()),
                    static::resolvePath($config[static::OPTION_KEY_OUTPUT], getcwd())
                );
            }

            $formatter = isset($config[static::OPTION_KEY_FORMATTER]) ? $config[static::OPTION_KEY_FORMATTER] : static::DEFAULT_OPTION_FORMATTER;
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
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected static function validateConfiguration(array $config)
    {
        if (empty($config[static::CONFIG_MAIN_KEY])) {
            throw new \InvalidArgumentException('compiler should needs to be configured through the extra.css-compiler setting');
        }

        if (!is_array($config[static::CONFIG_MAIN_KEY])) {
            throw new \InvalidArgumentException('the extra.css-compiler setting must be an array of objects');
        }

        foreach ($config[static::CONFIG_MAIN_KEY] as $options) {
            if (!is_array($options)) {
                throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[]." . static::OPTION_KEY_INPUT . ' array');
            }

            static::validateMandatoryOptions($options);
        }
    }

    /**
     * @param array $options
     *
     * @throws \InvalidArgumentException
     */
    protected static function validateMandatoryOptions(array $options)
    {
        foreach (static::$mandatoryOptions as $optionIndex) {
            if (!isset($options[$optionIndex])) {
                throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[].{$optionIndex} is required!");
            }

            switch ($optionIndex) {
                case static::OPTION_KEY_INPUT:
                    if (!is_array($options[$optionIndex])) {
                        throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[].{$optionIndex} should be array!");
                    }
                    break;
                case static::OPTION_KEY_OUTPUT:
                    if (!is_string($options[$optionIndex])) {
                        throw new \InvalidArgumentException('extra.' . static::CONFIG_MAIN_KEY . "[].{$optionIndex} should string!");
                    }
                    break;
            }
        }
    }
}
