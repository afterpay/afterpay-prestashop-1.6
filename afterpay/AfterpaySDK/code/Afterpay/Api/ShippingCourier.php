<?php

namespace Afterpay\Api;

/**
 	* Class ShippingCourier
 	*
 	* ShippingCourier can be used to store details about courier service
	*
 	* @package Afterpay\Api
  	* 
  	* @property string shippedAt
	* @property string name
	* @property string tracking
 	* @property string priority
	
*/
class ShippingCourier
{

	/**
	* Default Constructor
	* 
	* @param String Name
	* @param String Priority
 	* 
	*/
	public function __construct($name,$priority)
	{
		$this->setName($name);
		$this->setPriority($priority);		
	}	
	/**
	* Date by when it should be shipped
	*
	* @param string $shippedAt
	* 
 	* @return $this
 	*/
	protected function setShippedAt($shippedAt)
 	{
		$this->shippedAt = $shippedAt;
		return $this;
	}
 	/**
 	* Date by when it should be shipped
 	*
 	* @return string
	*/
 	protected function getShippedAt()
 	{
		return $this->ShippedAt;
	}
	/**
	* Name of the courier service
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
 	* Name of the courier service
 	*
 	* @return string
	*/
 	protected function getName()
 	{
		return $this->name;
	}
	/**
	* Tracking Id of Courier Service
	*
	* @param string $tracking
	* 
 	* @return $this
 	*/
	protected function setTracking($tracking)
 	{
		$this->tracking = $tracking;
		return $this;
	}
 	/**
 	* Tracking Id of Courier Service
 	*
 	* @return string
	*/
 	protected function getTracking()
 	{
		return $this->tracking;
	}
	/**
	* Priority of Courier Service
	*
	* @param string $priority
	* 
 	* @return $this
 	*/
	protected function setPriority($priority)
 	{
		$this->priority = $priority;
		return $this;
	}
 	/**
 	* Priority of Courier Service
 	* @return string
	*/
 	protected function getPriority()
 	{
		return $this->priority;
	}
}
