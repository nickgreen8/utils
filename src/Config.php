<?php
namespace N8G\Utils;

use N8G\Utils\Exceptions\ConfigException;

/**
 * This class acts as a central storage for all config data. This can be used in many
 * situations and on different sites.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Config
{
	/**
	 * Array of data to be used as the data store
	 * @var array
	 */
	private $data = array();

	/**
	 * This function is used to set a new item of data in the config data store. The
	 * function takes the name and the value of the data. Nothing is returned.
	 *
	 * @param  string $name  The name and the key to the data being stored
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public function setItem($name, $value)
	{
		//Set data in config
		$this->data[$name] = $value;
	}

	/**
	 * This function is used to get an item of data from the config data store. The
	 * function takes the name of the data and returns it's value.
	 *
	 * @param  string $name  The name and the key to the data being stored
	 * @return mixed         The value attributed to the key passed
	 */
	public function getItem($name)
	{
		//Check data is stored
		if (isset($this->data[$name])) {
			//Return the data
			return $this->data[$name];
		}
		throw new ConfigException('Data item not found.');
	}

	/**
	 * This function is used to check if an item is set in the config storatge bank.
	 * The name of the item to be requested is passed to the function and if it is set,
	 * the data is returned. If the data is not present, false is returned.
	 *
	 * @param  string $name The name of the item to check.
	 * @return mixed        The item of data if it is set. If it is not, FALSE.
	 */
	public function inConfig($name)
	{
		//Check for element in config
		if (isset($this->data[$name])) {
			return $this->data[$name];
		}
		//return default
		return false;
	}

	/**
	 * This function is used to reset the config data store.
	 *
	 * @return void
	 */
	public function clear()
	{
		$this->data = array();
	}

	/**
	 * This functions is used to set and get any data in config.
	 *
	 * @param  string $method The method that was called.
	 * @param  array  $args   An array of the arguments that were passed to the function.
	 * @return mixed          The item in config or void.
	 */
	public function __call($method, $args)
	{
		//Calculate the key
		$name = preg_replace("/^(get|set)/", '', $method);
		$key = '';
		for ($i = 0; $i < strlen($name); $i++) {
			if (preg_match("/[A-Z]/", $name[$i]) && strlen($key) > 1) {
				$key .= '-';
			}
			//Concatinate to key
			$key .= strtolower($name[$i]);
		}
		//Check if it is a get method
		if (preg_match("/^get/", $method)) {
			//Return data requested
			if (self::inConfig($key)) {
				return $this->data[$key];
			}
			throw new ConfigException('Data item not found.');
		}

		//Check if it is a set method
		if (preg_match("/^set/", $method)) {
			//Set config value
			$this->data[$key] = $args[0];
			//Return so stop function
			return;
		}

		//Throw exception by default
		throw new ConfigException('Invalid function called.');
	}

	// Non functions

	/**
	 * Returns the value of the data array.
	 *
	 * @return Array The array of config data
	 */
	public function getData()
	{
		//Return the data array
		return $this->data;
	}

	/**
	 * Returns the size of the data array.
	 *
	 * @return Integer The size of the data array
	 */
	public function size()
	{
		//Return the size of the data array
		return count($this->data);
	}
}