<?php
//Allows the control of the mock global variable
namespace {
	$mockFOpen = false;
	$mockFWrite = false;
}

namespace N8G\Utils {
	/**
	 * This is an override for this namespace ONLY! This will therefore allow me to dictate
	 * the return value of the function within the tests to foce the code into certain
	 * scenarios. If the function is called from within this namespace, whether the mock
	 * value should be returned or not is checked before returning that value or the result
	 * of the actual function call.
	 *
	 * @return mixed The mocked return value or the actual function result
	 */
	function fopen() {
		global $mockFOpen;
		if (isset($mockFOpen) && $mockFOpen === true) {
			return false;
		} else {
			return call_user_func_array('\fopen', func_get_args());
		}
	}

	/**
	 * Same as above. This is a mock function to simulate the fwrite function failing.
	 *
	 * @return mixed The mocked return value or the actual function result
	 */
	function fwrite() {
		global $mockFWrite;
		if (isset($mockFWrite) && $mockFWrite === true) {
			return false;
		} else {
			return call_user_func_array('\fwrite', func_get_args());
		}
	}

use N8G\Utils\Json,
	N8G\Utils\Log;

/**
 * Unit tests for the Json class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/London');
		Log::init('tests/fixtures/logs/', 'jsonTests.log');

		//Create directories if they don't exist
		if (!is_dir('./tests/fixtures/json/write/')) {
			mkdir('./tests/fixtures/json/write/');
		}
	}

	/**
	 * Cleans up after all tests
	 */
	public static function tearDownAfterClass()
	{
		Log::reset();
		exec('[ -d "tests/fixtures/json/write/" ] && rm -r tests/fixtures/json/write/*');
		exec('[ -d "tests/fixtures/logs/" ] && rm -r tests/fixtures/logs/');
	}

	// Tests

	/**
	 * Tests to see that if a JSON file exists.
	 *
	 * @test
	 * @dataProvider checkFileProvider
	 *
	 * @param  string  $file   The path to the file
	 * @param  boolean $exists Indicates whether the file should exist or not.
	 * @return void
	 */
	public function testFileExists($file, $exists)
	{
		$this->assertEquals($exists, Json::fileExists(sprintf('./tests/fixtures/json/read/%s.json', $file)));
	}

	/**
	 * Test to a JSON file can be read successfully and validated.
	 *
	 * @test
	 * @dataProvider readFilesProvider
	 *
	 * @param  string  $file  The path to the file
	 * @param  boolean $array Indicates whether the data should be returned as an array
	 * @return void
	 */
	public function testReadFile($file, $array)
	{
		//Check the file name
		if ($file !== 'success') {
			//Set expected message
			switch ($file) {
				case 'doesNotExist' :
					$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'The file specifed cannot be found.');
					break;
				case 'empty' :
					$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'The file specifed is empty.');
					break;
				case 'cantOpenFile' :
					$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'Could not open file!');

					global $mockFOpen;  
					$mockFOpen = true;
					break;
				case 'invalid' :
					$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'The JSON found is invalid.');
					break;
			}
		}

		//Perform function
		$json = Json::readFile(sprintf('./tests/fixtures/json/read/%s.json', $file), $array);

		//Check the test should pass
		if ($file === 'success') {
			//Check what should have been returned
			if ($array) {
				//Check an array was returned
				$this->assertArrayHasKey("test", $json);
				$this->assertContains("This JSON is valid", $json);
			} else {
				//Check that an object was returned
				$this->assertInstanceOf('stdClass', $json);
			}
		}
	}

	/**
	 * Check that valid JSON can be written to a file.
	 *
	 * @test
	 * @dataProvider writeProvider
	 *
	 * @param  mixed   $value     The value of the JSON as either a string or an array.
	 * @param  string  $file      The file name for the new file
	 * @param  string  $error     A string to detemine an expected error
	 * @return void
	 */
	public function testWriteToFile($value, $file, $error)
	{
		if ($error === 'invalid json') {
			$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'The JSON specified is invalid.');
		} elseif ($error === 'file open') {
			global $mockFOpen;
			$mockFOpen = true;

			$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'Could not open file!');
		} elseif ($error === 'file write') {
			global $mockFWrite;
			$mockFWrite = true;

			$this->setExpectedException('N8G\Utils\Exceptions\JsonException', 'The data could not be written to file!');
		}

		$outcome = Json::writeToFile($value, $file);
		$this->assertTrue($outcome);
		$this->assertFileExists($file);
	}

	/**
	 * Check that valid JSON can be written to a file. This file attempts to write an object into JSON.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testWriteToFileWithObject()
	{
		$obj = new \stdClass();
		$obj->test = "This is a test";

		$outcome = Json::writeToFile($obj, './tests/fixtures/json/write/test7.json');

		$this->assertTrue($outcome);
		$this->assertFileExists('./tests/fixtures/json/write/test7.json');
	}

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
	{
		$this->assertEquals($expected, Json::validate($value));
	}

	// Data providers

	public function checkFileProvider()
	{
		return array(
			array(
				'file'		=>	'success',
				'exists'	=>	true
			),
			array(
				'file'		=>	'invalid',
				'exists'	=>	true
			),
			array(
				'file'		=>	'doesNotExist',
				'exists'	=>	false
			),
			array(
				'file'		=>	'empty',
				'exists'	=>	true
			)
		);
	}

	public function readFilesProvider()
	{
		return array(
			array(
				'file'		=>	'success',
				'array'		=>	true
			),
			array(
				'file'		=>	'success',
				'array'		=>	false
			),
			array(
				'file'		=>	'invalid',
				'array'		=>	true
			),
			array(
				'file'		=>	'doesNotExist',
				'array'		=>	false
			),
			array(
				'file'		=>	'empty',
				'array'		=>	true
			),
			array(
				'file'		=>	'cantOpenFile',
				'array'		=>	true
			)
		);
	}

	public function writeProvider()
	{
		return array(
			array(
				'value'		=>	'{ "test": "This is a test" }',
				'file'		=>	'./tests/fixtures/json/write/test1.json',
				'error'		=>	'no error'
			),
			array(
				'value'		=>	'{ "test": "This is a test" }',
				'file'		=>	'./tests/fixtures/json/write/test1.json',
				'error'		=>	'file open'
			),
			array(
				'value'		=>	'{ "test": "This is a test" }',
				'file'		=>	'./tests/fixtures/json/write/test1.json',
				'error'		=>	'file write'
			),
			array(
				'value'		=>	'Testing',
				'file'		=>	'./tests/fixtures/json/write/test2.json',
				'error'		=>	'invalid json'
			),
			array(
				'value'		=>	array("Test" => "This is another test"),
				'file'		=>	'./tests/fixtures/json/write/test3.json',
				'error'		=>	'no error'
			),
			array(
				'value'		=>	array(1, '2', 3.00, true, false, null),
				'file'		=>	'./tests/fixtures/json/write/test4.json',
				'error'		=>	'no error'
			),
			array(
				'value'		=>	array(),
				'file'		=>	'./tests/fixtures/json/write/test5.json',
				'error'		=>	'no error'
			),
			array(
				'value'		=>	null,
				'file'		=>	'./tests/fixtures/json/write/test6.json',
				'error'		=>	'invalid json'
			)
		);
	}

	public function validateProvider()
	{
		return array(
			array(
				'value'		=>	'{ "test": "" }',
				'expected'	=>	true
			),
			array(
				'value'		=>	'{ "test":  }',
				'expected'	=>	false
			),
			array(
				'value'		=>	'{ \'test\':  }',
				'expected'	=>	false
			),
			array(
				'value'		=>	'{ "test": \'test\' }',
				'expected'	=>	false
			),
			array(
				'value'		=>	'{ "test": 1 }',
				'expected'	=>	true
			),
			array(
				'value'		=>	'{ "test": true }',
				'expected'	=>	true
			),
			array(
				'value'		=>	'{ "test": 1.5 }',
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

} // Close namespace