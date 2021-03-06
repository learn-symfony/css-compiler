<?php

namespace EM\CssCompiler\Tests\Environment;

/**
 * @since 0.1
 */
abstract class IntegrationTestSuite extends \PHPUnit_Framework_TestCase
{
    /**
     * able to invoke any non-static of object and return the result and throws exceptions if so
     *
     * useful to used to invoke non-public method of the class
     *
     * @param mixed  $object
     * @param string $methodName
     * @param array  $methodArguments
     *
     * @return mixed
     * @throws \Exception
     */
    protected function invokeMethod($object, $methodName, array $methodArguments = [])
    {
        $method = (new \ReflectionClass(get_class($object)))->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArguments);
    }

    /**
     * @return string
     */
    protected function getRootDirectory()
    {
        return dirname(__DIR__);
    }

    /**
     * @return string
     */
    protected function getSharedFixturesDirectory()
    {
        return static::getRootDirectory() . '/shared-fixtures';
    }

    /**
     * return content of the file in located in tests/shared-fixtures directory
     *
     * @param string $filename
     *
     * @return string
     */
    protected function getSharedFixtureContent(string $filename)
    {
        return file_get_contents(static::getSharedFixturesDirectory() . "/$filename");
    }

    protected function getCacheDirectory()
    {
        return dirname($this->getRootDirectory()) . '/var/cache/tests';
    }
}
