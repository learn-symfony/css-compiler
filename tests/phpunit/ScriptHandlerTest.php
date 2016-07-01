<?php

namespace EM\CssCompiler\Tests\PHPUnit;

use EM\CssCompiler\ScriptHandler;
use EM\CssCompiler\Tests\Environment\IntegrationTestSuite;

/**
 * @see ScriptHandler
 */
class ScriptHandlerTest extends IntegrationTestSuite
{
    /*** *************************** CONFIGURATION VALIDATION *************************** ***/
    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateConfigurationExpectedExceptionOnNotExistingKey()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [[]]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateConfigurationExpectedExceptionOnEmpty()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [[ScriptHandler::CONFIG_MAIN_KEY]]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateConfigurationExpectedExceptionOnNotArray()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [[ScriptHandler::CONFIG_MAIN_KEY => 'string']]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateConfigurationExpectedExceptionOptionIsNotArray()
    {
        $arr = [
            ScriptHandler::CONFIG_MAIN_KEY => [
                'string'
            ]
        ];
        $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [$arr]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     */
    function validateConfigurationOnValid()
    {
        $arr = [
            ScriptHandler::CONFIG_MAIN_KEY => [
                [
                    ScriptHandler::OPTION_KEY_INPUT  => ['string'],
                    ScriptHandler::OPTION_KEY_OUTPUT => 'string'
                ]
            ]
        ];
        $result = $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [$arr]);
        $this->assertTrue($result);
    }

    /*** *************************** OPTIONS VALIDATION *************************** ***/
    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateOptionsExpectedExceptionOnMissingInput()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateOptions', [[ScriptHandler::OPTION_KEY_OUTPUT]]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateOptionsExpectedExceptionOnMissingOutput()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateOptions', [[ScriptHandler::OPTION_KEY_INPUT]]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateOptionsExpectedExceptionOnInputNotArray()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateOptions', [[
            ScriptHandler::OPTION_KEY_INPUT  => 'string',
            ScriptHandler::OPTION_KEY_OUTPUT => 'string'
        ]]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    function validateOptionsExpectedExceptionOnOutputNotString()
    {
        $this->invokeMethod(new ScriptHandler(), 'validateOptions', [[
            ScriptHandler::OPTION_KEY_INPUT  => ['string'],
            ScriptHandler::OPTION_KEY_OUTPUT => ['string']
        ]]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     */
    function validateOptionsOnValid()
    {
        $options = [
            [ScriptHandler::OPTION_KEY_INPUT => ['string'], ScriptHandler::OPTION_KEY_OUTPUT => 'string']
        ];
        $result = $this->invokeMethod(new ScriptHandler(), 'validateOptions', [$options]);

        $this->assertTrue($result);
    }
}
