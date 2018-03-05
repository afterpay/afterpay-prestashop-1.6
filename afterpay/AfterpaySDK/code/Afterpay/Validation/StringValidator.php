<?php

namespace Afterpay\Validation;

/**
  * Class StringValidator
  *
  * @package Afterpay\Validation
*/
class StringValidator
{
	/**
	* Helper method for validating address fields
	*
	* @param mixed $argument
	* @param string|null $argumentName
	* @return bool
	*/
	public static function validate($key, $value = null)
	{		
		switch ($key)
      {
           		 case 'suburb':
           			if(empty($value)){
           				throw new \InvalidArgumentException("$key is a required field");
           			}
           			break;
           		 case 'state':
           			if(empty($value)){
           				throw new \InvalidArgumentException("$key is a required field");           				
           			}
           			break;
           		case 'postcode':
           			if(empty($value)){
           				throw new \InvalidArgumentException("$key is a required field");   
           			}
           			break;
           		case 'countryCode':
           			if(empty($value)){
           				throw new \InvalidArgumentException("$key is a required field"); 
           			}
           			break;
           		case 'line1':
           			if(empty($value)){
           				throw new \InvalidArgumentException("$key is a required field"); 
           			}
                break;
              case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                  throw new \InvalidArgumentException("Please provide valid email address"); 
                }
           			break;
              case 'surname':
                if(empty($value)){
                  throw new \InvalidArgumentException("$key is a required field"); 
                }
                break;
              case 'quantity':
                if (trim($argument) != null && !is_numeric($argument)) {
                  throw new \InvalidArgumentException("$key is not a valid numeric value"); 
                }
                break;
              case 'redirectConfirmUrl':
                if(empty($value)){
                  throw new \InvalidArgumentException("$key is a required field"); 
                }
                break;
              case 'redirectCancelUrl':
                if(empty($value)){
                  throw new \InvalidArgumentException("$key is a required field"); 
                }
                break;
                case 'displayName':
                if(empty($value)){
                  throw new \InvalidArgumentException("$key is a required field"); 
                }    
           		default:
           			break;
           }
		return true;
	}
}