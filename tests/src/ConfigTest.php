<?php
namespace N8G\Utils;

use N8G\Utils\Config;

/**
 * Unit tests for the Config class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
	// Tests

	/**
	 * Test that data can be set in config.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @return void
	 */
	public function testSetItem($key, $value)
	{
		//Create new config object
		$config = new Config;

		$config->setItem($key, $value);

		$this->assertArrayHasKey($key, $config->getData());
		$this->assertContains($value, $config->getData());
		$this->assertEquals($value, $config->getData()[$key]);
	}

	/**
	 * Test that data can be retrieved.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key   The key for config data
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function testGetItem($key, $value)
	{
		//Create new config object
		$config = new Config;

		$config->setItem($key, $value);
		$data = $config->getItem($key);

		$this->assertEquals($value, $data);
	}

	/**
	 * Test that a check can be made to see if an item is in the config data array.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @return void
	 */
	public function testInConfig($key, $value)
	{
		//Create new config object
		$config = new Config;

		$config->clear();

		$inConfig = $config->inConfig($key);

		$this->assertFalse($inConfig);

		$func = sprintf('set%s', ucwords($key));
		$config->$func($value);

		$inConfig = $config->inConfig($key);

		$this->assertEquals($value, $inConfig);
	}

	/**
	 * Test that the generic setter works as expected.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @return void
	 */
	public function testGenericSetter($key, $value)
	{
		//Create new config object
		$config = new Config;

		$func = sprintf('set%s', ucwords($key));
		$config->$func($value);

		$this->assertArrayHasKey($key, $config->getData());
		$this->assertContains($value, $config->getData());
		$this->assertEquals($value, $config->getData()[$key]);
	}

	/**
	 * Test that the generic getter works as expected.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key   The key for config data
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function testGenericGetter($key, $value)
	{
		//Create new config object
		$config = new Config;

		$config->setItem($key, $value);
		$func = sprintf('get%s', ucwords($key));
		$data = $config->$func();

		$this->assertEquals($value, $data);
	}

	/**
	 * Test that the config data can be cleared.
	 *
	 * @test
	 * @dataProvider dataProvider
	 *
	 * @param  string $key    The key for config data
	 * @param  mixed  $value  The value of the data
	 * @return void
	 */
	public function testClear($key, $value)
	{
		//Create new config object
		$config = new Config;

		$func = sprintf('set%s', ucwords($key));
		$config->$func($value);

		$config->clear();

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
		//Create new config object
		$config = new Config;

		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Data item not found.');

		$config->getItem($key);
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
		//Create new config object
		$config = new Config;

		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Data item not found.');

		$func = sprintf('get%s', ucwords($key));
		$data = $config->$func();
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
	public function testCallback($key, $value)
	{
		//Create new config object
		$config = new Config;

		$this->setExpectedException('N8G\Utils\Exceptions\ConfigException', 'Invalid function called.');

		$config->$key($value);
	}

	/**
	 * Tests that the get data function works as expected.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetData()
	{
		//Create new config object
		$config = new Config;

		$this->assertEmpty($config->getData());

		$config->setTest('This is a test');

		$this->assertArrayHasKey('test', $config->getData());
		$this->assertContains('This is a test', $config->getData());
		$this->assertEquals('This is a test', $config->getData()['test']);

		$config->setInTest(true);

		$this->assertArrayHasKey('in-test', $config->getData());
		$this->assertContains(true, $config->getData());
		$this->assertEquals(true, $config->getData()['in-test']);
	}

	/**
	 * Tests that the size function works as expected. This should return the number of
	 * records held within the config class.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testSize()
	{
		//Create new config object
		$config = new Config;

		$this->assertEquals(0, $config->size());

		$config->setTest('This is a test');

		$this->assertEquals(1, $config->size());

		$config->setInTest(true);

		$this->assertEquals(2, $config->size());
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