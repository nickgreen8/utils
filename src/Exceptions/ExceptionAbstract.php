<?php
namespace N8G\Utils\Exceptions;

/**
 * This is an abstract class that should be extended by all exceptions. The class extends the
 * PHP exception class. The class contains generic functionality that is required in the custom
 * exceptions created should be placed in here and can be overloaded in any child classes.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
abstract class ExceptionAbstract extends \Exception
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