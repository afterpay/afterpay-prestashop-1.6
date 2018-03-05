<?php

namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
 	* Class ShippingAddress
 	*
 	* Inherits Parent Address Class.
	*
 	* @package Afterpay\Api
  	* 
*/
class ShippingAddress extends Address
{
	/*
	* Call Parent Address Constructor and pass  address Array
	*/

	public function __construct($shippingAddress)
	{
		parent::__construct($shippingAddress);
		
	}
	/**
	* Validate suburb, state, postCode, countryCode and Phone Number
	* 
	* Throw Exception if fails
	* @return null
	*/
	public function validateShippingAddress()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}	
	
}