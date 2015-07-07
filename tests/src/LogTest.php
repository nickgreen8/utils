<?php
//Allows the control of the mock global variable
namespace {
	$mockIsDir = false;
	$mockMkdir = false;
	$mockIsWritable = false;
	$mockChmod = false;
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
	function is_dir() {
		global $mockIsDir;
		if (isset($mockIsDir) && $mockIsDir === true) {
			return false;
		} else {
			return call_user_func_array('\is_dir', func_get_args());
		}
	}
	/**
	 * Same as above. This is a mock function to simulate the chmod function failing.
	 *
	 * @return mixed The mocked return value or the actual function result
	 */
	function mkdir() {
		global $mockMkdir;
		if (isset($mockMkdir) && $mockMkdir === true) {
			return false;
		} else {
			return call_user_func_array('\mkdir', func_get_args());
		}
	}
	/**
	 * Same as above. This is a mock function to simulate the chmod function failing.
	 *
	 * @return mixed The mocked return value or the actual function result
	 */
	function is_writeable() {
		global $mockIsWritable;
		if (isset($mockIsWritable) && $mockIsWritable === true) {
			return false;
		} else {
			return call_user_func_array('\is_writeable', func_get_args());
		}
	}
	/**
	 * Same as above. This is a mock function to simulate the chmod function failing.
	 *
	 * @return mixed The mocked return value or the actual function result
	 */
	function chmod() {
		global $mockChmod;
		if (isset($mockChmod) && $mockChmod === true) {
			return false;
		} else {
			return call_user_func_array('\chmod', func_get_args());
		}
	}

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
		$log = Log::init();
		//Check the file is created
		$this->assertFileExists(sprintf('logs/%s.log', date('Y-m-d')));
		//Check what is returned
		$this->assertInstanceOf('N8G\Utils\Log', $log);
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
		$instance = Log::init('tests/fixtures/logs/', 'utils.log');
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*FATAL.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*ERROR.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*WARNING.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*NOTICE.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*INFO.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*DEBUG.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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
		$this->assertRegExp(sprintf("/.*?\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2} \[IP\: (?:\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}.*|\s{15})?\].*SUCCESS.*\- %s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
	}

	/**
	 * Tests that a 'custom' log entry can be made.
	 *
	 * @test
	 * @dataProvider textProvider
	 * @depends testInit
	 *
	 * @return void
	 */
	public function testCustom($text)
	{
		Log::custom($text);
		$this->assertRegExp(sprintf("/.*?%s.*?/", $text), file_get_contents('./tests/fixtures/logs/utils.log'));
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

	/**
	 * Tests the get file function of returns the expected type of element.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetFile()
	{
		//Reset the log
		Log::reset();
		//Initilise
		$log = Log::init('tests/fixtures/logs/', 'getFile.log');

		$this->assertTrue(is_resource($log->getFile()));
		$this->assertEquals('stream', get_resource_type($log->getFile()));
	}

	/**
	 * This test ensures that if a file creation fails, it fails in the correct ways.
	 *
	 * @test
	 * @dataProvider createProvider
	 *
	 * @return void
	 */
	public function testCreateFileFails($directory, $file, $error)
	{
		//Set the expected outcome
		switch ($error) {
			case 'mkdir' :
				global $mockIsDir, $mockMkdir;
				$mockIsDir = true;
				$mockMkdir = true;

				$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'Log file not created');
				break;
			case 'chmod' :
				global $mockIsWritable, $mockChmod;
				$mockIsWritable = true;
				$mockChmod = true;

				$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'The directoy is not writeable');
				break;
			case 'fopen' :
				global $mockFOpen;
				$mockFOpen = true;

				$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'Could not create log file!');
				break;
		}

		//Perfomrm the action
		$log = Log::init();
		Log::reset();
		$this->invokeMethod($log, 'createFile', array('directory' => $directory, 'file' => $file));
	}

	/**
	 * This test ensures that a file can be created in a none writable folder.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testCreateFileChangesMode()
	{
		//Create a read only folder
		mkdir('./tests/fixtures/logs/readonly/', 0444);

		//Perfomrm the action
		$log = Log::init();
		Log::reset();
		$this->invokeMethod($log, 'createFile', array('directory' => './tests/fixtures/logs/readonly/', 'file' => 'created.log'));

		$this->assertFileExists('./tests/fixtures/logs/readonly/created.log');
	}

	/**
	 * Tests that the write to file function writes in the expected format.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testWriteToFile()
	{}

	/**
	 * This function checks that the right category is returned. This should be the
	 * string representation that will display the different log message types in the
	 * files.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetCategory()
	{
		//Reset the log
		Log::reset();
		//Initilise
		$log = Log::init('tests/fixtures/logs/', 'category.log');

		//Fatal
		$this->assertEquals("\033[1;37m\033[41m FATAL \033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::FATAL)));

		//Error
		$this->assertEquals("\033[1;31m\033[1mERROR  \033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::ERROR)));

		//Warning
		$this->assertEquals("\033[0;33m\033[1mWARNING\033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::WARN)));

		//Notice
		$this->assertEquals("\033[0;36m\033[1mNOTICE \033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::NOTICE)));

		//Info
		$this->assertEquals("\033[0;34m\033[1mINFO   \033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::INFO)));

		//Debug
		$this->assertEquals("\033[0;37m\033[1mDEBUG  \033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::DEBUG)));

		//Success
		$this->assertEquals("\033[0;32m\033[1mSUCCESS\033[0m", $this->invokeMethod($log, 'getCategory', array('cat' => Log::SUCCESS)));

		//Invalid
		$this->setExpectedException('N8G\Utils\Exceptions\LogException', 'An undefined category specified: 7');
		$this->invokeMethod($log, 'getCategory', array('cat' => 7));
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

	public function createProvider()
	{
		return array(
			array(
				'directory'	=>	'./tests/fixtures/logs/',
				'file'		=>	'fail.log',
				'error'		=>	'mkdir'
			),
			array(
				'directory'	=>	'./tests/fixtures/logs/',
				'file'		=>	'fail.log',
				'error'		=>	'chmod'
			),
			array(
				'directory'	=>	'./tests/fixtures/logs/',
				'file'		=>	'fail.log',
				'error'		=>	'fopen'
			)
		);
	}

	// Private Helpers

	/**
	 * Call protected/private method of a class.
	 *
	 * @param  object &$object Instantiated object that we will run method on.
	 * @param  string $method  Method name to call
	 * @param  array  $params  Array of parameters to pass into method.
	 * @return mixed           Method return.
	 */
	private function invokeMethod(&$object, $method, array $params = array())
	{
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($method);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $params);
	}
}

} // Close namespace