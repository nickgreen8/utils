<?php
namespace N8G\Utils\Exceptions;

use N8G\Utils\Exceptions\ExceptionAbstract,
	N8G\Utils\Exceptions\ConfigException,
	N8G\Utils\Exceptions\JsonException,
	N8G\Utils\Exceptions\LogException,
	N8G\Utils\Log;

/**
 * Unit tests for the exceptions within the utils package.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/London');
		Log::init('tests/fixtures/logs/', 'configTests.log');
	}

	/**
	 * Cleans up after all tests
	 */
	public static function tearDownAfterClass()
	{
		Log::reset();
	}

	// Tests

	public function testAbstract()
	{}

	public function testDefaultConfigException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'This is a test', 3, null);
		throw new ConfigException('This is a test');
	}

	public function testOverwrittenCodeConfigException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'This is another test', 1, null);
		throw new ConfigException('This is another test', 1);
	}

	public function testDefaultJsonException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'This is a test', -1, null);
		throw new JsonException('This is a test');
	}

	public function testOverwrittenCodeJsonException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'This is another test', 1, null);
		throw new JsonException('This is another test', 1);
	}

	public function testDefaultLogException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'This is a test', -1, null);
		throw new LogException('This is a test');
	}

	public function testOverwrittenCodeLogException()
	{
		$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'This is another test', 1, null);
		throw new LogException('This is another test', 1);
	}
}