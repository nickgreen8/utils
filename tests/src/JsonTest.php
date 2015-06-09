<?php
namespace N8G\Utils\Tests;

use N8G\Utils\Json,
	N8G\Utils\Log;

class JsonTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/London');
		Log::init('tests/fixtures/logs/', 'jsonTests.log');
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
	 * Tests to see that if a JSON file exists and that it is valid.
	 *
	 * @test
	 * @dataProvider readFilesProvider
	 *
	 * @param  string  $file     The path to the file
	 * @param  boolean $expected Indicates whether the test should pass or not.
	 * @return void
	 */
	public function testFileExists($file, $expected)
	{}

	/**
	 * Test to a JSON file can be read successfully and validated.
	 *
	 * @test
	 * @dataProvider readFilesProvider
	 *
	 * @param  string  $file     The path to the file
	 * @param  boolean $expected Indicates whether the test should pass or not.
	 * @return void
	 */
	public function testReadFile($file, $expected)
	{}

	/**
	 * Check that valid JSON can be written to a file.
	 *
	 * @test
	 * @dataProvider writeProvider
	 *
	 * @param  mixed   $value    The value of the JSON as either a string or an array.
	 * @param  boolean $expected Indicates whether the test should pass or not.
	 * @return void
	 */
	public function testWriteToFile($value, $expected)
	{}

	/**
	 * Checks that JSON can be validated successfully.
	 *
	 * @test
	 * @dataProvider validateProvider
	 *
	 * @param  mixed   $value    The value of the JSON as either a string or an array.
	 * @param  boolean $expected Indicates whether the test should pass or not.
	 * @return void
	 */
	public function testValidateJson($value, $expected)
	{}

	// Data providers

	public function readFilesProvider()
	{
		return array(
			array(
				'file'		=>	'tests/fixtures/JsonTests/success.json',
				'expected'	=>	true
			),
			array(
				'file'		=>	'tests/fixtures/JsonTests/invalid.json',
				'expected'	=>	false
			),
			array(
				'file'		=>	'tests/fixtures/JsonTests/doesNotExist.json',
				'expected'	=>	false
			)
		);
	}

	public function writeProvider()
	{
		return array(
			array(
				'value'		=>	'',
				'expected'	=>	true
			),
			array(
				'value'		=>	array(),
				'expected'	=>	true
			),
			array(
				'value'		=>	'',
				'expected'	=>	false
			),
			array(
				'value'		=>	array(),
				'expected'	=>	false
			)
		);
	}

	public function validateProvider()
	{
		return array(
			array(
				'value'		=>	'[ "test":  ]',
				'expected'	=>	true
			),
			array(
				'value'		=>	'[ \'test\':  ]',
				'expected'	=>	false
			),
			array(
				'value'		=>	'[ "test": \'test\' ]',
				'expected'	=>	false
			),
			array(
				'value'		=>	'[ "test": 1 ]',
				'expected'	=>	true
			),
			array(
				'value'		=>	'[ "test": true ]',
				'expected'	=>	true
			),
			array(
				'value'		=>	'[ "test": 1.5 ]',
				'expected'	=>	true
			),
			array(
				'value'		=>	'',
				'expected'	=>	false
			),
			array(
				'value'		=>	null,
				'expected'	=>	false
			),
			array(
				'value'		=>	1,
				'expected'	=>	false
			),
			array(
				'value'		=>	1.5,
				'expected'	=>	false
			),
			array(
				'value'		=>	true,
				'expected'	=>	false
			)
		);
	}
}