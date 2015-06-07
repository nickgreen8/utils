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
     * Cleans up after a tests
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
     * @return void
     */
    public function testGetItem($key, $value)
    {
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
     * @return void
     */
    public function testGenericGetter($key, $value, $config)
    {
    	$func = sprintf('get%s', ucwords($key));
    	$data = Config::$func($value);

    	$this->assertEquals($value, $data);
    }

    /**
     * Test that the config data can be cleared.
     *
     * @test
     * @depends testInit
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testClear($key, $value, $config)
    {
    	$func = sprintf('set%s', ucwords($key));
    	Config::$func($value);

    	Config::clear();

    	$this->assertEmpty($config->getdata());
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