<?php
namespace N8G\Utils;

/**
 * This class acts as a central storage for all config data. This can be used in many
 * situations and on different sites. This is a static class and can be called from
 * anywhere.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Config
{
	/**
	 * Array of data to be used as the data store
	 * @var array
	 */
	private static $data = array();

	/**
	 * This function is used to initilised the config object. The data is retreved
	 * from the database and stored within the data array. Nothing is passed and nothing
	 * is retuned.
	 *
	 * @return void
	 */
	public static function init()
	{
		Log::info('Initilising config class');
	}

	/**
	 * This function is used to set a new item of data in the config data store. The
	 * function takes the name and the value of the data. Nothing is returned.
	 *
	 * @param  string $name  The name and the key to the data being stored
	 * @param  mixed  $value The value of the data
	 * @return void
	 */
	public static function setItem($name, $value)
	{
		Log::info(sprintf('Setting Config[\'%s\'] to "%s"', $name, $value));
		//Set data in config
		self::$data[$name] = $value;
	}

	/**
	 * This function is used to get an item of data from the config data store. The
	 * function takes the name of the data and returns it's value.
	 *
	 * @param  string $name  The name and the key to the data being stored
	 * @return mixed         The value attributed to the key passed
	 */
	public static function getItem($name)
	{
		Log::info(sprintf('Getting Config[\'%s\']', $name));
		//Check data is stored
		if (isset(self::$data[$name])) {
			//Return the data
			return self::$data[$name];
		}
		return NULL;
	}

	/**
	 * This function is used to check if an item is set in the config storatge bank.
	 * The name of the item to be requested is passed to the function and if it is set,
	 * the data is returned. If the data is not present, false is returned.
	 *
	 * @param  string $name The name of the item to check.
	 * @return mixed        The item of data if it is set. If it is not, FALSE.
	 */
	public static function inConfig($name)
	{
		Log::info(sprintf('Looking for %s in config', $name));
		//Check for element in config
		if (isset(self::$data[$name])) {
			return self::$data[$name];
		}
		//return default
		return false;
	}

	/**
	 * This function is used to reset the config data store.
	 *
	 * @return void
	 */
	public static function clear()
	{
		self::$data = array();
	}	
}