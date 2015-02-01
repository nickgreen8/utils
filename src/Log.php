<?php
namespace N8G\Utils;

use N8G\Utils\Exceptions\UnableToOpenFileException;

/**
 * This function acts as a logging mechinism. This can be used in many situations
 * and on different sites. This is a static class and can be called from anywhere.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Log
{
	//Log categoies
	const CUSTOM  = -1;
	const FATAL   = 0;
	const ERROR   = 1;
	const WARN    = 2;
	const NOTICE  = 3;
	const INFO    = 4;
	const DEBUG   = 5;
	const SUCCESS = 6;

	/**
	 * The spacing needed to format the log file
	 */
	const LOG_SPACING = PHP_EOL;

	/**
	 * Instance of this class
	 * @var object
	 */
	private static $instance;

	/**
	 * The file to be written to
	 * @var pointer
	 */
	private static $file;

	/**
	 * This is the default constructor. The directory to put the files as well as the
	 * name of the file.
	 *
	 * @param string $directory The folder to put the log files
	 * @param string $filename  The name of the file to create
	 */
	private function __construct($directory, $filename)
	{
		//set the name of the file
		self::$file = $this->getFile($directory, $filename);
	}

	/**
	 * Closes the file if needed.
	 */
	public function __destruct()
	{
		if (self::$file !== null) {
			fclose(self::$file);
		}
	}

	/**
	 * This function is used to initilise the log file. The directory and the file
	 * name is passed as well as the name of the file to be set.
	 *
	 * @param  string $directory The folder to hold the log file
	 * @param  string $filename  The name of the log file
	 * @return void
	 */
	public static function init($directory = 'logs/', $filename = NULL)
	{
		$directory = $directory;
		//Check for instance of the class
		if (self::$instance === null) {
			self::$instance = new self($directory, $filename);
		}
	}

	/**
	 * This function is used to setup or create the holding folder and the log file.
	 * The directory of the file is passed and if it does not exist, it is created.
	 * The name of the file to be created is also passed.
	 *
	 * @param  string $directory The file where the file is
	 * @param  string $filename  The name of the file
	 * @return void
	 */
	private static function getFile($directory, $filename)
	{
		//Create file name if there is none
		if ($filename === NULL) {
			$filename = date('Y-m-d') . '.log';
		}

		//Check the director exists
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		try {
			//Check the files exists
			if (false === self::$file = fopen($directory . $filename, 'a')) {
				throw new UnableToOpenFileException('Could not create log file!');
			}
			return self::$file;
		} catch (UnableToOpenFileException $e) {}
	}

	/**
	 * This function is used to write the the relevant log file. The category of the
	 * message and the message is passed to the function. Nothign is returned.
	 *
	 * @param  int    $cat The category of the message
	 * @param  string $msg The message to be written to the file
	 * @return void
	 */
	private static function writeToFile($cat, $msg)
	{
		//check if the class has been initiated
		self::init();

		if ($cat !== self::CUSTOM) {
			$message = sprintf(
				'{%s} %s - %s%s',
				date('d\/m\/Y H:i:s'),
				self::getCategory($cat),
				$msg,
				PHP_EOL
			);
		} else {
			$message = sprintf('%s%s', $msg, PHP_EOL);
		}
		fwrite(self::$file, $message);
	}

	/**
	 * This function is used to specify the category in a log message. The severity
	 * is passed to the function and the string representing it is returned.
	 *
	 * @param  int    $cat The constant for the log message category
	 * @return string      The string for the category part of the the log message
	 */
	private static function getCategory($cat)
	{
		switch ($cat) {
			case self::FATAL :
				$category = "\033[1;37m\033[41m FATAL ";
				break;
			case self::ERROR :
				$category = "\033[1;31m\033[1mERROR  ";
				break;
			case self::WARN :
				$category = "\033[0;33m\033[1mWARNING";
				break;
			case self::NOTICE :
				$category = "\033[0;36m\033[1mNOTICE ";
				break;
			case self::INFO :
				$category = "\033[0;34m\033[1mINFO   ";
				break;
			case self::DEBUG :
				$category = "\033[0;37m\033[1mDEBUG  ";
				break;
			case self::SUCCESS :
				$category = "\033[0;32m\033[1mSUCCESS";
				break;
		}
		return $category . "\033[0m";
	}

	/**
	 * This method is used to log a fatal error.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function fatal($msg)
	{
		self::writeToFile(self::FATAL, $msg);
	}

	/**
	 * This method is used to log an error.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function error($msg)
	{
		self::writeToFile(self::ERROR, $msg);
	}

	/**
	 * This method is used to log a warning.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function warn($msg)
	{
		self::writeToFile(self::WARN, $msg);
	}

	/**
	 * This method is used to log a notice.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function notice($msg)
	{
		self::writeToFile(self::NOTICE, $msg);
	}

	/**
	 * This method is used to log an info message.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function info($msg)
	{
		self::writeToFile(self::INFO, $msg);
	}

	/**
	 * This method is used to log a debug message.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function debug($msg)
	{
		self::writeToFile(self::DEBUG, $msg);
	}

	/**
	 * This method is used to log a success.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function success($msg)
	{
		self::writeToFile(self::SUCCESS, $msg);
	}

	/**
	 * This method is used to log a custom message.
	 *
	 * @param  string $msg The message to be logged
	 * @return void
	 */
	public static function custom($msg)
	{
		self::writeToFile(self::CUSTOM, $msg);
	}
}