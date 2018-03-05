<?php

namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
 	* Class Item
 	*
 	* Item Details.
	*
 	* @package Afterpay\Api
  	* 
  	* @property string sku
	* @property string name
 	* @property string quantity
	* @property string price
*/
class Item
{
	/**
    * Item Name $name
    */
    public $name;
    /**
    * Item SKU $sku
    */
    public $sku;
    /**
    * Item Quantity $quantity
    */
    public $quantity;
    /**
    * Item Price $name
    */
    public $price;

	/**
    * Default Constructor
    * 
    * @param string $name
    * @param string $sku
    * @param string $quantity
    * @param Object $price
    * 
    */
    public function __construct($name,$sku,$quantity,$priceObj)
    {
        $this->setName($name);
        $this->setSku($sku);
        $this->setQuantity($quantity);
        $this->setPrice($priceObj);

    }
	/**
	* Stock keeping unit corresponding (SKU) to item.
	*
	* @param string $sku
	* 
 	* @return $this
	*/
	protected function setSku($sku)
 	{
		$this->sku = $sku;
		return $this;
	}
	/**
	* Stock keeping unit corresponding (SKU) to item.
 	*
	* @return string
	*/
	protected function getSku()
	{
		return $this->sku;
	}
	/**
	* Item name. 	
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
	* Item name. 
	*
	* @return string
	*/
 	protected function getName()
	{
		return $this->name;
 	}
	/**
	* Number of a particular item. 
	*
	* @param string $quantity
	* 
	* @return $this
	*/
	protected function setQuantity($quantity)
	{
		$this->quantity = $quantity;
		return $this;
	}
	/**
	* Number of a particular item. 
	*
	* @return string
	*/
	protected function getQuantity()
	{
		return $this->quantity;
	}
	/**
	* Item cost
	*
	* @param \Afterpay\Api\Amount $price
	* 
	* @return $this
	*/
	protected function setPrice($price)
	{
		$this->price = $price;
		return $this;
	}
	/**
	* Item cost
	*
	* @return \Afterpay\Api\Amount
	*/
	protected function getPrice()
	{
		return $this->price;
	}
	/**
	* 
	* Validate name and quantity
	*
	* Throw Exception if fails
	*
	* @return null
	*/
	public function validateItem()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}	
	
	
}