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
    public function validateConfigurationExpectedExceptionOnNotExistingKey()
    {
        $this->validateConfiguration([]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateConfigurationExpectedExceptionOnEmpty()
    {
        $this->validateConfiguration([ScriptHandler::CONFIG_MAIN_KEY => '']);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateConfigurationExpectedExceptionOnNotArray()
    {
        $this->validateConfiguration([ScriptHandler::CONFIG_MAIN_KEY => 'string']);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateConfigurationExpectedExceptionOptionIsNotArray()
    {
        $this->validateConfiguration([ScriptHandler::CONFIG_MAIN_KEY => ['string']]);
    }

    /**
     * @see ScriptHandler::validateConfiguration
     * @test
     */
    public function validateConfigurationOnValid()
    {
        $args = [
            ScriptHandler::CONFIG_MAIN_KEY => [
                [ScriptHandler::OPTION_KEY_INPUT => ['string'], ScriptHandler::OPTION_KEY_OUTPUT => 'string']
            ]
        ];

        $this->assertTrue($this->validateConfiguration($args));
    }

    private function validateConfiguration($args)
    {
        return $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [$args]);
    }
    /*** *************************** OPTIONS VALIDATION *************************** ***/
    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnMissingInput()
    {
        $this->validateOptions([[ScriptHandler::OPTION_KEY_OUTPUT => 'output']]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnMissingOutput()
    {
        $this->validateOptions([ScriptHandler::OPTION_KEY_INPUT => 'input']);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnInputNotArray()
    {
        $this->validateOptions([
            ScriptHandler::OPTION_KEY_INPUT  => 'string',
            ScriptHandler::OPTION_KEY_OUTPUT => 'string'
        ]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnOutputNotString()
    {
        $this->validateOptions([
            ScriptHandler::OPTION_KEY_INPUT  => ['string'],
            ScriptHandler::OPTION_KEY_OUTPUT => ['string']
        ]);
    }

    /**
     * @see ScriptHandler::validateOptions
     * @test
     */
    public function validateOptionsOnValid()
    {
        $this->assertTrue(
            $this->validateOptions([
                ScriptHandler::OPTION_KEY_INPUT  => ['string'],
                ScriptHandler::OPTION_KEY_OUTPUT => 'string'
            ])
        );
    }

    /**
     * @param array $config
     *
     * @return bool
     */
    private function validateOptions($config)
    {
        return $this->invokeMethod(new ScriptHandler(), 'validateOptions', [[$config]]);
    }
}
