<?php
namespace N8G\Cms\Exceptions\Components;

use N8G\Cms\Exceptions\ExceptionAbstract;

/**
 * This exception is thown when a file cannot be created.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class UnableToOpenFileException extends ExceptionAbstract
{
	/**
	 * Default custom exception constructor
	 *
	 * @param string         $message  A message to embed in the exception
	 * @param integer        $code     A user defined error
	 * @param Exception|null $previous A previous exception that has caused this exception
	 */
	public function __construct($message, $code = -1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}