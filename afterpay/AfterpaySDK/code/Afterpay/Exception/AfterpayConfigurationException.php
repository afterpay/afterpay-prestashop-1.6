<?php

namespace Afterpay\Exception;
 
 /**
 	* Class AfterpayConfigurationException
 	*
 	* @package Afterpay\Exception
 */

class AfterpayConfigurationException extends \Exception
{

	/**
	* Default Constructor
	*
	* @param string|null $message
	* @param int  $code
	*/
	public function __construct($message = null, $code = 0)
	{
		parent::__construct($message, $code);
	}
}