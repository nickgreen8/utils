<?php
namespace N8G\Utils;

/**
 * This function acts as a validation mechinism. The class will contain functions that will
 * be used for validation.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Validation
{
	/**
	 * This function is used to validate an e-mail address. The e-mail passed and a boolean
	 * indicator is returned, indicating whether the e-mail is valid.
	 *
	 * @param  string  $email The e-mail address to be validated
	 * @return boolean        The result of the validation
	 */
	public function isEmail($email)
	{
		if (preg_match("/^[A-Za-z0-9\.\-\_\%\+]+@[A-Za-z0-9\-\_\%\+]+\.(?:[a-z]{2,4}\.)?[a-z]{2,4}$/u", trim($email), $output)) {
			//return match
			return true;
		}

		//Return false by default
		return false;
	}

	/**
	 * This function is used to validate a date. The date is passed with the expected format and
	 * a boolean indicator is returned, indicating whether the date is valid.
	 *
	 * @param  string  $date    Date of birth to validate.
	 * @param  string  $format The format of the date that should be validated.
	 * @return boolean         The result of the validation
	 */
	public function isDate($date, $format = 'dd/mm/yyyy')
	{
		//Select regex
		switch ($format) {
			case 'dd/mm/yy' :
				$regex = '/^([0-3][0-9])\/([0-1][0-9])\/(\d{2})$/u';
				break;
			case 'dd/mm/yyyy' :
				$regex = '/^([0-3][0-9])\/([0-1][0-9])\/(\d{4})$/u';
				break;
			case 'dd-mm-yy' :
				$regex = '/^([0-3][0-9])\-([0-1][0-9])\-(\d{2})$/u';
				break;
			case 'dd-mm-yyyy' :
				$regex = '/^([0-3][0-9])\-([0-1][0-9])\-(\d{4})$/u';
				break;
			case 'dd.mm.yy' :
				$regex = '/^([0-3][0-9])\.([0-1][0-9])\.(\d{2})$/u';
				break;
			case 'dd.mm.yyyy' :
				$regex = '/^([0-3][0-9])\.([0-1][0-9])\.(\d{4})$/u';
				break;
		}

		//Separate string
		preg_match($regex, trim($date), $sections);

		//Check for a match
		if (!isset($sections) || count($sections) !== 4) {
			//return pattern error
			return false;
		}

		//Set patter params
		$day   = $sections[1];
		$month = $sections[2];
		$year  = $sections[3];

		//Check months
		if (intval($month) > 12 || intval($month) < 1) {
			//Return year invalid
			return false;
		}

		//Check days
		switch (intval($month)) {
			case 2  : $max = intval($year) % 4 === 0 ? 29 : 28; break;
			case 4  :
			case 6  :
			case 9  :
			case 11 : $max = 30;                          break;
			default : $max = 31;                          break;
		}
		if (intval($day) > $max || intval($day) < 1) {
			//Return year invalid
			return false;
		}

		//Return true by default
		return true;
	}
}