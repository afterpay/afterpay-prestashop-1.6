<?php

namespace Afterpay\Validation;

/**
  * Class PriceValidator
  *
  * @package Afterpay\Validation
*/
class PriceValidator
{
	/**
  * Afterpay Currency
  */
	const CURRENCY_CODE = 'AUD';
	/**
	* Helper method for validating an argument if it is numeric
	*
	* @param mixed     $key
	* @param string|null $value
	* @return bool
	*/
	public static function validate($key, $value = null)
	{
		switch ($key)
           {
           		case 'amount':
           			if (trim($value) == null) {
						throw new \InvalidArgumentException("$key is not a valid price value");
					}
           			break;
           		case 'currency':
           			if (trim($value) != self::CURRENCY_CODE) {
						throw new \InvalidArgumentException("$key is not a valid ");
					}
           			break;
           		default:
           			break;
           	}
    }
  /* Validate Current Cart Total Amount with Afterpay Order Total Amount
	*
	* @param string Afterpay Total Order Object
	* @param string Current Cart Total Amount
	* @return bool
	*/
    public function validateCartTotal($afterpayOrder,$currentCartTotal)
    {
    	if($afterpayOrder->totalAmount->amount == $currentCartTotal)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
}