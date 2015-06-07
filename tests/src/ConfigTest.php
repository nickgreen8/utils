<?php
namespace N8G\Utils\Tests;

use N8G\Utils\Config,
	N8G\Utils\Log;

class ConfigTest extends \PHPUnit_Framework_TestCase
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
	 * Cleans up after each test
	 */
	protected function tearDown()
	{
		Config::clear();
	}

	/**
	 * Cleans up after all tests
	 */
	public static function tearDownAfterClass()
	{
		Log::reset();
	}

	// Tests

	/**
	 * Test that the the config class can be initilised.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testInit()
	{
		$config = Config::init();
		$this->assertRegExp("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*INFO.*\- Initilising config class.*?/", file_get_contents('./tests/fixtures/logs/configTests.log'));
		$this->assertEmpty($config->getData());

		//Return instance of the config class
		return $config;
	}

	/**
	 * Test that data can be set in config.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @param  object $config Instance of the config class
	 * @return void
	 */
	public function testSetItem($key, $value, $config)
	{
		Config::setItem($key, $value);

		$this->assertArrayHasKey($key, $config->getData());
		$this->assertContains($value, $config->getData());
		$this->assertEquals($value, $config->getData()[$key]);
	}

	/**
	 * Test that data can be retrieved.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key   The key for config data
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function testGetItem($key, $value)
	{
		Config::setItem($key, $value);
		$data = Config::getItem($key);

		$this->assertEquals($value, $data);
	}

	/**
	 * Test that a check can be made to see if an item is in the config data array.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @param  object $config Instance of the config class
	 * @return void
	 */
	public function testInConfig($key, $value, $config)
	{
		Config::clear();

		$inConfig = Config::inConfig($key);

		$this->assertFalse($inConfig);

		$func = sprintf('set%s', ucwords($key));
		Config::$func($value);

		$inConfig = Config::inConfig($key);

		$this->assertEquals($value, $inConfig);
	}

	/**
	 * Test that the generic setter works as expected.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @param  object $config Instance of the config class
	 * @return void
	 */
	public function testGenericSetter($key, $value, $config)
	{
		$func = sprintf('set%s', ucwords($key));
		Config::$func($value);

		$this->assertArrayHasKey($key, $config->getData());
		$this->assertContains($value, $config->getData());
		$this->assertEquals($value, $config->getData()[$key]);
	}

	/**
	 * Test that the generic getter works as expected.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key   The key for config data
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function testGenericGetter($key, $value)
	{
		Config::setItem($key, $value);
		$func = sprintf('get%s', ucwords($key));
		$data = Config::$func();

		$this->assertEquals($value, $data);
	}

	/**
	 * Test that the config data can be cleared.
	 *
	 * @test
	 * @depends testInit
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @param  object $config Instance of the config class
	 * @return void
	 */
	public function testClear($key, $value, $config)
	{
		$func = sprintf('set%s', ucwords($key));
		Config::$func($value);

		Config::clear();

		$this->assertEmpty($config->getdata());
	}

	/**
	 * Check that errors are thrown if data is not in config when getting retrieved.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key The key for config data
	 * @return void
	 */
	public function testFailedGetter($key)
	{
		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Data item not found.');

		Config::getItem($key);
	}

	/**
	 * Check that errors are thrown if data is not in config when getting retrieved
	 * using the generic getter.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key The key for config data
	 * @return void
	 */
	public function testFailedGenericGetter($key)
	{
		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Data item not found.');

		$func = sprintf('get%s', ucwords($key));
		$data = Config::$func();
	}

	/**
	 * Check that not just anything can be called against the Config class. The keys
	 * that are used in the other tests in the data provider are used as funtion
	 * calls.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key   The key for config data
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function testStaticCallback($key, $value)
	{
		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Invalid function called.');

		Config::$key($value);
	}

	// Data providers

	public function dataProvider()
	{
		return array(
			array(
				'key'	=>	'string',
				'value'	=>	"string test"
			),
			array(
				'key'	=>	'char',
				'value'	=>	'characters test'
			),
			array(
				'key'	=>	'int',
				'value'	=>	10
			),
			array(
				'key'	=>	'bool',
				'value'	=>	true
			),
			array(
				'key'	=>	'float',
				'value'	=>	1.5
			)
		);
	}
}