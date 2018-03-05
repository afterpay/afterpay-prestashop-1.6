<?php
	 
namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
 	* Class Consumer
	*
 	* @package Afterpay\Api
  	* 
  	* @property string phoneNumber
	* @property string givenName
	* @property string surname
	* @property string email
*/

class Consumer
{
	/**
    * Customer Phone Number
    */
	public $phoneNumber;
	/**
    * Customer Given Name (Can include First and Middle Name)
    */
	public $givenNames;
	/**
    * Customer Surname
    */
	public $surname;
	/**
    * Customer Email
    */
	public $email;
	

	/**
	* Default Constructor
	* 
	* @param Array $consumer Array
 	* 
	*/
	public function __construct($consumer)
	{
		$this->setPhoneNumber($consumer['phoneNumber']);
		$this->setEmail($consumer['email']);
		$this->setGivenNames($consumer['givenName']);
		$this->setSurname($consumer['surName']);		
	}	
	/**
	* Email address representing the consumer. 
 	*
	* @param string $email
	* 
	* @return $this
	*/
	protected function setEmail($email)
 	{
		$this->email = $email;
 		return $this;
	}	
	/**
 	* Email address representing the consumer.
 	*
	* @return string
	*/
	protected function getEmail()
 	{
		return $this->email;
 	}
 	/**
	* Given name of the consumer.
	*
	* @param string $givenNames
	* 
	* @return $this
	*/
	protected function setGivenNames($givenNames)
	{
		$this->givenNames = $givenNames;
		return $this;
	}
	
	/**
	* Given name of the consumer.
	*
	* @return string
	*/
	protected function getGivenNames()
	{
		return $this->givenNames;
	}
	/**
	* Surname of the consumer.
	*
	* @param string $surname
	* 
	* @return $this
	*/
	protected function setSurname($surname)
	{
		$this->surname = $surname;
		return $this;
	}
	
	/**
	* Surname name of the consumer.
	*
	* @return string
	*/
	protected function getSurname()
	{
		return $this->surname;
	}
	/**
	* Phone number representing the consumer.
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
	* Consumer Phone Number
	*
	* @return string
	*/
	protected function getPhoneNumber()
	{
		return $this->phoneNumber;
	}
	/**
	* 
	* Validate email and surname.
	*
	* Throw Exception when validation fails
	*
	* @return null
	*/
	public function validateConsumer()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}
} 