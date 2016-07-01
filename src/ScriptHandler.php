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
        $currentDirectory = getcwd();

        foreach ($extra[static::CONFIG_MAIN_KEY] as $config) {
            foreach ($config[static::OPTION_KEY_INPUT] as $value) {
                $processor->attachFiles("{$currentDirectory}/{$value}", "{$currentDirectory}/{$config[static::OPTION_KEY_OUTPUT]}");
            }

            $formatter = isset($config[static::OPTION_KEY_FORMATTER]) ? $config[static::OPTION_KEY_FORMATTER] : static::DEFAULT_OPTION_FORMATTER;

            $processor->processFiles($formatter);
        }
        $processor->saveOutput();
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

        return static::validateOptions($config);
    }

    /**
     * @param array $config
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected static function validateOptions(array $config)
    {
        foreach ($config[static::CONFIG_MAIN_KEY] as $index => $option) {
            if (!is_array($option)) {
                throw new \InvalidArgumentException("the extra.css-compiler[{$index}]." . static::OPTION_KEY_INPUT . ' array');
            }

            static::validateMandatoryOptions($option);
        }

        return true;
    }


    /**
     * @param array $config
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected static function validateMandatoryOptions(array $config)
    {
        foreach (static::$mandatoryOptions as $option) {
            if (empty($config[$option])) {
                throw new \InvalidArgumentException("The extra.css-compiler[].{$option} required!");
            }
        }
        static::validateInputOption($config);
        static::validateOutputOption($config);

        return true;
    }
    /**
     * @param array $config
     *
     * @return bool
     */
    protected static function validateInputOption(array $config)
    {
        if (!is_array($config[static::OPTION_KEY_INPUT])) {
            throw new \InvalidArgumentException('The extra.css-compiler[].' . static::OPTION_KEY_INPUT . ' should be array!');
        }

        return true;
    }

    /**
     * @param array $config
     *
     * @return bool
     */
    protected static function validateOutputOption(array $config)
    {
        if (!is_string($config[static::OPTION_KEY_OUTPUT])) {
            throw new \InvalidArgumentException('The extra.css-compiler[].' . static::OPTION_KEY_OUTPUT . ' should string!');
        }

        return true;
    }
}
