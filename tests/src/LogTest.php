<?php
namespace N8G\Utils;

use N8G\Utils\Log;

/**
 * Unit tests for the Log class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class LogTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
    {
    	date_default_timezone_set('Europe/London');
    }

    /**
     * Cleans up after a test
     */
    public static function tearDownAfterClass()
    {
		exec('[ -d "tests/fixtures/logs/" ] && rm -r tests/fixtures/logs/');
    }

    // Tests

	/**
	 * Tests that a log file can be initialised.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testInit()
	{
		//Init without arguments
		Log::init();
		//Check the file is created
		$this->assertFileExists(sprintf('logs/%s.log', date('Y-m-d')));
		//Reset the log and clean up
		Log::reset();
        exec('rm -r logs/');

		//Init with an existing directory
		Log::init('tests/fixtures/');
		//Check the file is created
		$this->assertFileExists(sprintf('tests/fixtures/%s.log', date('Y-m-d')));
		//Reset the log and clean up
		Log::reset();
        exec('rm tests/fixtures/*.log');

		//Init with a new directory
		Log::init('tests/fixtures/logs/');
		//Check the file is created
		$this->assertFileExists(sprintf('tests/fixtures/logs/%s.log', date('Y-m-d')));
		//Reset the log
		Log::reset();

		//Init with directory with no slash
		Log::init('tests/fixtures/logs', 'test.log');
		//Check the file is created
		$this->assertFileExists('tests/fixtures/logs/test.log');
		//Reset the log
		Log::reset();

		//Init with directory and filename
		Log::init('tests/fixtures/logs/', 'utils.log');
		//Check the file is created
		$this->assertFileExists('tests/fixtures/logs/utils.log');
	}

	/**
	 * Tests that a 'fatal' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testFatal($text)
	{
		Log::fatal($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*FATAL.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that an 'error' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testError($text)
	{
		Log::error($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*ERROR.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'warning' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testWarning($text)
	{
		Log::warn($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*WARNING.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'notice' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testNotice($text)
	{
		Log::notice($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*NOTICE.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'info' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testInfo($text)
	{
		Log::info($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*INFO.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'debug' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testDebug($text)
	{
		Log::debug($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*DEBUG.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'success' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testSuccess($text)
	{
		Log::success($text);
		$this->assertRegExp(sprintf("/.*?\{\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}\}.*SUCCESS.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that the log file can be reset to it's initial state.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testReset()
	{
		$log = Log::init();
		Log::reset();

		//Check that the reset has worked
		$this->assertNull($log->getFile());
	}

	// Data providers

	public function textProvider()
	{
		return array(
			array(
				'text' => 'This is a test'
			),
			array(
				'text' => 1234567890
			),
			array(
				'text' => 1234.5678
			),
			array(
				'text' => 0
			),
			array(
				'text' => true
			),
			array(
				'text' => false
			),
			array(
				'text' => null
			)
		);
	}
}