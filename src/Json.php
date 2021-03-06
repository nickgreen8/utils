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
	public function fileExists($file)
	{
		if (file_exists($file)) {
			return true;
		}

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
	public function readFile($file, $array = false)
	{
		//Check for file
		if (!$this->fileExists($file)) {
			throw new JsonException('The file specifed cannot be found.');
		}

		//Check the size of the file
		if (filesize($file) < 1) {
			throw new JsonException('The file specifed is empty.');
		}

		//Get the data from the file
		if (false === $content = fopen($file, "r")) {
			throw new JsonException('Could not open file!');
		}

		$json = fread($content, filesize($file));
		fclose($content);

		//Check the JSON is valid
		if (!$this->validate($json)) {
			throw new JsonException('The JSON found is invalid.');
		}

		return json_decode($json, $array);
	}

	/**
	 * This function is used to write out a JSON file. The data that is to be written must be
	 * passed as an array, string or an object. The file path and name must then also be passed.
	 * If there is an error NULL is returned. If there is no error, true is returned.
	 *
	 * @param  mixed  $data The data to be encoded and then written
	 * @param  string $file The path to the file
	 * @return boolean|void
	 */
	public function writeToFile($data, $file)
	{
		//Validate JSON
		if (!$this->validate(is_string($data) ? $data : json_encode($data))) {
			throw new JsonException('The JSON specified is invalid.');
		}

		//Check the files exists
		if (false === $file = fopen($file, 'w')) {
			throw new JsonException('Could not open file!');
		}

		//Write to file
		if (fwrite($file, is_string($data) ? $data : json_encode($data)) === FALSE) {
			//Throw error
			throw new JsonException('The data could not be written to file!');
		}

		//Close the file
		fclose($file);
		return true;
	}

	/**
	 * This function is used to validate a JSON string. The string to be validated is passed to
	 * the function and a boolean value indicating whether the JSON is valid is returned.
	 *
	 * @param  string  $json The JSON to be validated.
	 * @return boolean       Indicates whether the JSON is valid.
	 */
	public function validate($json)
	{
		//Decode the JSON
		$data = json_decode($json);

		//Check the decode was successful
		if ($data !== null && json_last_error() === JSON_ERROR_NONE && preg_match("/^(\{|\[).*(\}|\])$/", str_replace("\n", '', trim(($json))))) {
			//Return successful
			return true;
		}

		//Return false by default
		return false;
	}
}