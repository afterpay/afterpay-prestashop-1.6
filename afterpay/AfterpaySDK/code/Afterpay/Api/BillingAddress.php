<?php

namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
 	* Class BillingAddress
 	*
 	* Inherits Parent Address Class.
	*
 	* @package Afterpay\Api
  	* 
*/
class BillingAddress extends Address
{
	/*
	* Call Parent Address Constructor and pass billing address Array
	*/

	public function __construct($billingAddress)
	{
		parent::__construct($billingAddress);
		
	}
	/**
	* Validate suburb, state, postCode, countryCode and Phone Number
	* 
	* Throw exception if error occurs
	*
	* @return null
	*/
	public function validateBillingAddress()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}	
	
}