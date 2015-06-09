<?php
namespace N8G\Utils;

use N8G\Utils\Exceptions\JsonException;

/**
 * This class contains methods that are used to manipulate json files. All the
 * methods are static.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Json
{
	/**
	 * This function is used to check if a file exists. The file location and name
	 * is passed to the function. A boolean value is then returned indicating
	 * whether the file exists or not.
	 *
	 * @param  string $file The file name including path to it.
	 * @return bool
	 */
	public static function fileExists($file)
	{
		Log::notice(sprintf('Looking for file: %s', $file));

		if (file_exists($file)) {
			Log::success('File found');
			return true;
		}

		Log::error('File not found');
		return false;
	}

	/**
	 * This function is used to read the conetent of a JSON file. The path and file name
	 * is passed first and if the content is required in array format, a boolean true
	 * should also be passed. If there is an error, NULL is returned. If there is no
	 * error, the JSON is returned as an object or an array.
	 *
	 * @param  string  $file  File path and name
	 * @param  boolean $array Incicator for an array to be returned (Default: false)
	 * @return mixed
	 */
	public static function readFile($file, $array = false)
	{
		Log::info(sprintf('Getting the data from %s', $file));

		//Check for file
		if (!self::fileExists($file)) {
			throw new JsonException('The file specifed cannot be found.');
		}

		//Check the size of the file
		if (filesize($file) < 1) {
			Log::error('The file size suggests the file is empty');
			throw new JsonException('The file specifed is empty.');
		}

		//Get the data from the file
		if (false === $content = fopen($file, "r")) {
			throw new JsonException('Could not open file!');
		}

		$json = fread($content, filesize($file));
		fclose($content);

		Log::success(sprintf('JSON retreived: %s', $json));
		return json_decode($json, $array);
	}

	/**
	 * This function is used to write out a JSON file. The data that is to be written must be
	 * passed as an array. The file path and name must then also be passed. If there is an
	 * error NULL is returned. If there is no error, true is returned.
	 *
	 * @param  array  $data The data to be encoded and then written
	 * @param  string $file The path to the file
	 * @return boolean|void
	 */
	public static function writeToFile(array $data, $file)
	{
		Log::info('Opening file');

		//Check the files exists
		if (false === $file = fopen($file, 'w')) {
			throw new JsonException('Could not open file');
		}
		Log::success('File opened');

		//Write to file
		fwrite($file, json_encode($data));
		Log::success('Data written to file');

		//Close the file
		fclose($file);
		return true;
	}
}