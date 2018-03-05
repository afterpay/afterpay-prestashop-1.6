<?php

namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
 	* Class Discount
 	*
 	* Discount Details.
	*
 	* @package Afterpay\Api
  	* 
*/
class Discount
{
	/**
    * Default Constructor
    * 
    * @param string $name
    * @param onject $price
    */
    public function __construct($name,$price)
    {
        $this->setDisplayName($name);
        $this->setAmount($price);
    }
	/**
	* Display name for the Discount
	*
	* @param string $displayName
	* 
 	* @return $this
	*/
	public function setDisplayName($displayName)
 	{
		$this->displayName = $displayName;
		return $this;
	}
	/**
	* Display name for the Discount
 	*
	* @return string
	*/
	public function getDisplayName()
	{
		return $this->displayName;
	}
	/**
	* Discount Amount
	*
	* @param \Afterpay\Api\Amount $amount
	* 
 	* @return $this
	*/
	public function setAmount($amount)
 	{
		$this->amount = $amount;
		return $this;
	}
	/**
	* Discount Amount
 	*
	* @return \Afterpay\Api\Amount
	*/
	public function getAmount()
	{
		return $this->amount;
	}
	/**
	* 
	* Validate displayName
	* Throw Exception if fails
	*
	* @return null
	*/
	public function validateDiscounts()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}	
	
}