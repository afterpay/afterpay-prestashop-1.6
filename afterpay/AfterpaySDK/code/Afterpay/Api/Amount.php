<?php

namespace Afterpay\Api;

use Afterpay\Validation\PriceValidator;
 
/**
    * Class Amount
    * Used by Total Order Amount, Shipping Amount, Discount Amount and Tax Amount
    *
    * @package Afterpay\Api
    *
    * @property string currency
    * @property string amount
*/
class Amount
{

    /**
    * Amount $amount
    */
    public $amount;
    /**
    * Currency $currency
    */
    public $currency;

    /**
    * Default Constructor
    * 
    * @param string $amount
    * @param string $currency 
    * 
    */
    public function __construct($amount,$currency)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }
	/* 
    * Currency code
    *
    * @param string $currency. 3-letter currency code. Only "AUD" is supported
    * 
    * @return $this
    */
	protected function setCurrency($currency)
	{
    	$this->currency = $currency;
        return $this;
    }
    /* 
    * Currency code
    *
    * @return $this
    */
    protected function getCurrency()
	{
		 return $this->currency;
	}
	/* 
    * Order Amount
    *
    * @param string $amount.
    * 
    * @return $this
    */
	protected function setAmount($amount)
	{
    	$this->amount = $amount;
        return $this;
    }
    /* 
    * Order Amount
    *
    * @return $this
    */
    protected function getAmount()
	{
		 return $this->amount;
	}
    /**
    * Check if all property values are assigned and correct
    * Need to validate amount and currency
    *
    * Throw Exception if validation fails
    * @return null
    */
    protected function validatePrice()
    {
        foreach ($this as $key => $value) 
        {
            PriceValidator::validate($key,$value);
        }
       return null;
    }
	
}

