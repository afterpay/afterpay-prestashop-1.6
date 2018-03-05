<?php
	
namespace Afterpay\Api;

/**
 	* Class Transaction
	*
 	* @package Afterpay\Api
*/

class Transaction
{
	/**
	* Amount being collected.
	*
	* @param \Afterpay\Api\Amount $totalAmount
 	* 
 	* @return $this
	*/
	public function setTotalAmount($totalAmount)
 	{
		$this->totalAmount = $totalAmount;
		return $this;
	}
	/**
	* Amount being collected.
	*
	* @return \Afterpay\Api\Amount
	*/
	public function getTotalAmount()
 	{
		return $this->totalAmount;
 	}
 	/**
	* Consumer details
	*
	* @param \Afterpay\Api\Consumer $consumer
 	* 
 	* @return $this
	*/
	public function setConsumer($consumer)
 	{
		$this->consumer = $consumer;
		return $this;
	}
	/**
	* Consumer details
	*
	* @return \Afterpay\Api\Consumer
	*/
	public function getConsumer()
 	{
		return $this->consumer;
 	}
 	/**
	* Consumer billing details
	*
	* @param \Afterpay\Api\BillingAddress $billing
 	* 
 	* @return $this
	*/
	public function setBilling($billing)
 	{
		$this->billing = $billing;
		return $this;
	}
	/**
	* Consumer billing details
	*
	* @return \Afterpay\Api\BillingAddress
	*/
	public function getBilling()
 	{
		return $this->billing;
 	}
 	/**
	* Consumer shipping details
	*
	* @param \Afterpay\Api\ShippingAddress $shipping
 	* 
 	* @return $this
	*/
	public function setShipping($shipping)
 	{
		$this->shipping = $shipping;
		return $this;
	}
	/**
	* Consumer shipping details
	*
	* @return \Afterpay\Api\ShippingAddress 
	*/
	public function getShipping()
 	{
		return $this->shipping;
 	}
 	/**
	* Courier details
	*
	* @param \Afterpay\Api\ShippingCourier $courier
 	* 
 	* @return $this
	*/
	public function setCourier($courier)
 	{
		$this->courier = $courier;
		return $this;
	}
	/**
	* Courier details
	*
	* @return \Afterpay\Api\ShippingCourier
	*/
	public function getCourier()
 	{
		return $this->courier;
 	}
 	/**
	* List of order items
	*
	* @param \Afterpay\Api\Item $items
	* 
	* @return $this
	*/
	public function setItems($items)
	{
		$this->items = $items;
		return $this;
	}
	
	/**
	* List of order items
	*
	* @return \Afterpay\Api\Item
	*/
	public function getItems()
	{
		return $this->items;
	}
	/**
	* List of discounts
	*
	* @param \Afterpay\Api\Discount $discounts
	* 
	* @return $this
	*/
	public function setDiscounts($discounts)
	{
		$this->discounts = $discounts;
		return $this;
	}
	/**
	* List of discounts
	*
	* @return \Afterpay\Api\Discount
	*/
	public function getDiscounts()
	{
		return $this->discounts;
	}
	/**
	* Tax Amount being collected.
	*
	* @param \Afterpay\Api\Amount $taxAmount
 	* 
 	* @return $this
	*/
	public function setTaxAmount($taxAmount)
 	{
		$this->taxAmount = $taxAmount;
		return $this;
	}
	/**
	* Tax Amount being collected.
	*
	* @return \Afterpay\Api\Amount
	*/
	public function getTaxAmount()
 	{
		return $this->taxAmount;
 	}
 	/**
	* Shipping Amount being collected.
	*
	* @param \Afterpay\Api\Amount $shippingAmount
 	* 
 	* @return $this
	*/
	public function setShippingAmount($shippingAmount)
 	{
		$this->shippingAmount = $shippingAmount;
		return $this;
	}
	/**
	* Tax Amount being collected.
	*
	* @return \Afterpay\Api\Amount
	*/
	public function getShippingAmount()
 	{
		return $this->shippingAmount;
 	}
 	/**
	* Get Merchant Redirect URL's
	*
	* @param \Afterpay\Api\RedirectUrls $merchant
 	* 
 	* @return $this
	*/
	public function setMerchantUrl($merchant)
 	{
		$this->merchant = $merchant;
		return $this;
	}
	/**
	* Tax Amount being collected.
	*
	* @return \Afterpay\Api\RedirectUrls
	*/
	public function getMerchantUrl()
 	{
		return $this->merchant;
 	}
 	/**
	* Get Merchant reference Id merchantReference
 	* 
 	* @return $this
	*/
	public function setMerchantReference($merchantReference)
 	{
		$this->merchantReference = $merchantReference;
		return $this;
	}
	/**
	* Tax Amount being collected.
	*
	* @return string
	*/
	public function getMerchantReference()
 	{
		return $this->merchantReference;
 	}

}