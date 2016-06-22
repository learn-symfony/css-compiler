<?php

namespace EM\Tests\Environment;

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
    protected function invokeMethod($object, string $methodName, array $methodArguments = [])
    {
        $method = (new \ReflectionClass(get_class($object)))->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArguments);
    }

    public static function getRootDirectory() : string
    {
        return dirname(__DIR__);
    }

    public static function getSharedFixturesDirectory() : string
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
    public static function getSharedFixtureContent(string $filename) : string
    {
        return file_get_contents(static::getSharedFixturesDirectory() . "/$filename");
    }
}
