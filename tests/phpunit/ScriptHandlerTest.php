<?php

namespace EM\CssCompiler\Tests\PHPUnit;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use Composer\Script\Event;
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

        $this->assertNull($this->validateConfiguration($args));
    }

    /**
     * @see ScriptHandler::validateConfiguration
     *
     * @param $args
     *
     * @return bool
     */
    private function validateConfiguration($args)
    {
        return $this->invokeMethod(new ScriptHandler(), 'validateConfiguration', [$args]);
    }
    /*** *************************** OPTIONS VALIDATION *************************** ***/
    /**
     * @see ScriptHandler::validateMandatoryOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnMissingInput()
    {
        $this->validateMandatoryOptions([[ScriptHandler::OPTION_KEY_OUTPUT => 'output']]);
    }

    /**
     * @see ScriptHandler::validateMandatoryOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnMissingOutput()
    {
        $this->validateMandatoryOptions([ScriptHandler::OPTION_KEY_INPUT => 'input']);
    }

    /**
     * @see ScriptHandler::validateMandatoryOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnInputNotArray()
    {
        $this->validateMandatoryOptions([
            ScriptHandler::OPTION_KEY_INPUT  => 'string',
            ScriptHandler::OPTION_KEY_OUTPUT => 'string'
        ]);
    }

    /**
     * @see ScriptHandler::validateMandatoryOptions
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function validateOptionsExpectedExceptionOnOutputNotString()
    {
        $this->validateMandatoryOptions([
            ScriptHandler::OPTION_KEY_INPUT  => ['string'],
            ScriptHandler::OPTION_KEY_OUTPUT => ['string']
        ]);
    }

    /**
     * @see   ScriptHandler::validateMandatoryOptions
     * @test
     *
     * @group tester
     */
    public function validateOptionsOnValid()
    {
        $this->assertNull(
            $this->validateMandatoryOptions(
                [
                    ScriptHandler::OPTION_KEY_INPUT  => ['string'],
                    ScriptHandler::OPTION_KEY_OUTPUT => 'string'
                ]
            )
        );
    }

    /**
     * @see ScriptHandler::validateMandatoryOptions
     *
     * @param array $config
     *
     * @return bool
     */
    private function validateMandatoryOptions($config)
    {
        return $this->invokeMethod(new ScriptHandler(), 'validateMandatoryOptions', [$config, 1]);
    }

    /*** *************************** INTEGRATION *************************** ***/
    /**
     * @see   ScriptHandler::generateCSS
     * @test
     */
    public function generateCSS()
    {
        $composer = (new Composer());
        /** @var RootPackage|\PHPUnit_Framework_MockObject_MockObject $rootPackage */
        $rootPackage = $this->getMockBuilder(RootPackage::class)
            ->setConstructorArgs(['css-compiler', 'dev-master', 'dev'])
            ->setMethods(['getExtra'])
            ->getMock();
        /** @var IOInterface|\PHPUnit_Framework_MockObject_MockObject $io */
        $io = $this->getMockBuilder(IOInterface::class)->getMock();

        $output = $this->getCacheDirectory() . '/' . __FUNCTION__ . '.css';
        @unlink($output);

        $extra = [
            'css-compiler' => [
                [
                    'format' => 'compact',
                    'input'  => [
                        $this->getSharedFixturesDirectory() . '/less'
                    ],
                    'output' => $output
                ]
            ]
        ];

        $rootPackage->expects($this->once())
            ->method('getExtra')
            ->willReturn($extra);
        $composer->setPackage($rootPackage);

        $event = new Event('onInstall', $composer, $io);

        ScriptHandler::generateCSS($event);
        $this->assertFileExists($output);
    }
}
