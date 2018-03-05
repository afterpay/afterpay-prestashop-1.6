<?php

namespace Afterpay\Api;

/**
 	* Class Address
 	*
 	* Customer Address object can be extended for Billing or Shipping Address.
	*
 	* @package Afterpay\Api
  	* 
  	* @property string name
	* @property string line1
	* @property string line2
	* @property string postCode
	* @property string state
	* @property string suburb
	* @property string countryCode
	* @property string phoneNumber
*/
class Address
{

	/**
    * Customer Name
    */
	protected $name;
	/**
    * Customer Line1 Address
    */
	protected $line1;
	/**
    * Customer Line2 Address
    */
	protected $line2;
	/**
    * Customer Post Code
    */
	protected $postcode;
	/**
    * Customer State
    */
	protected $state;
	/**
    * Customer Suburb
    */
	protected $suburb;
	/**
    * Customer Country Code
    */
	protected $countryCode;
	/**
    * Customer Phone Number
    */
	protected $phoneNumber;

	/**
	* Default Constructor
	*
	* Will set values for all address properties
	*
	* @param Array $address 
 	* 
	*/
	public function __construct($address)
	{
		$this->setName($address['name']);
		$this->setLine1($address['line1']);
		$this->setLine2($address['line2']);
		$this->setPostcode($address['postcode']);
		$this->setState($address['state']);
		$this->setSuburb($address['suburb']);
		$this->setCountryCode($address['countryCode']);
		$this->setPhoneNumber($address['phoneNumber']);
		
	}	
	/**
	* Name of the customer at this address.
	*
	* @param string $name
	* 
 	* @return $this
 	*/
	protected function setName($name)
 	{
		$this->name = $name;
		return $this;
	}
	 
 	/**
 	* Name of the customer at this address.
 	*
 	* @return string
	*/
 	protected function getName()
 	{
		return $this->name;
	}
	/**
 	* Line 1 of the Address (eg. number, street, etc).
	*
	* @param string $line1
 	* 
 	* @return $this
	*/
	protected function setLine1($line1)
    {
        $this->line1 = $line1;
        return $this;
    }
    /**
	* Line 1 of the Address (eg. number, street, etc).
	*
	* @return string
 	*/
    protected function getLine1()
	{
		return $this->line1;
 	}
 	/**
 	* Line 2 of the Address (eg. suite, apt #, etc.).
	*
	* @param string $line2
 	* 
 	* @return $this
	*/
 	protected function setLine2($line2)
 	{
 		$this->line2 = $line2;
 		return $this;
 	}
 	/**
	* Line 2 of the Address (eg. suite, apt #, etc.).
	*
	* @return string
 	*/
 	protected function getLine2()
	{
		return $this->line2;
	}	
 	/**
	* Post Code.
 	*
 	* @param string $postcode
	* 
 	* @return $this
 	*/	
	protected function setPostcode($postcode)
	{
		$this->postcode = $postcode;
		return $this;
	}
	/**
 	* Post Code.
 	*
	* @return string
 	*/
	protected function getPostcode()
	{
		return $this->postcode;
	}
	/**
	* State Code.
 	*
 	* @param string $state
	* 
 	* @return $this
 	*/	
	protected function setState($state)
	{
		$this->state = $state;
		return $this;
	}
	/**
 	* State Code.
 	*
	* @return string
 	*/
	protected function getState()
	{
		return $this->state;
	}
	/**
	* Suburb.
 	*
 	* @param string $suburb
	* 
 	* @return $this
 	*/	
	protected function setSuburb($suburb)
	{
		$this->suburb = $suburb;
		return $this;
	}
	/**
 	* Suburb.
 	*
	* @return string
 	*/
	protected function getSuburb()
	{
		return $this->suburb;
	}
	/**
	* Country Code.
 	*
 	* @param string $countryCode
	* 
 	* @return $this
 	*/	
	protected function setCountryCode($countryCode)
	{
		$this->countryCode = $countryCode;
		return $this;
	}
	/**
 	* Country Code.
 	*
	* @return string
 	*/
	protected function getCountryCode()
	{
		return $this->countryCode;
	}
	/**
	* Customer phone number
	* 
	* @param string $phoneNumber
	* 
	* @return $this
	*/
	protected function setPhoneNumber($phoneNumber)
	{
		$this->phoneNumber = $phoneNumber;
		return $this;
	}
	
	/**
	* Customer phone number
	*
	* @return string
	*/
	protected function getPhoneNumber()
	{
		return $this->phoneNumber;
	}
}