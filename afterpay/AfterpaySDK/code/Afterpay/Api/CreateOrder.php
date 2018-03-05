<?php

namespace Afterpay\Api;

require_once(dirname(__FILE__) . "/../Core/Call.php");
require_once(dirname(__FILE__) . "/../Exception/AfterpayConfigurationException.php");

use Afterpay\Core\Call;
use Afterpay\Core\AfterpayConfig;
use Afterpay\Exception\AfterpayConfigurationException;	

/**
	* Class CreateOrder
	*
	* Call CreateOrder API to create Afterpay order
	*
	* @package Afterpay\Core
*/

class CreateOrder
{
	/**
	* Default Constructor
	*
	* 
	* Assign values for Call
	* @param Array $input 
 	* 
	*/
	protected $call;

	public function __construct( $input = NULL )
	{
		$this->call = new Call( $input );
	}

	/**
	* Call Create order API
	*
	* Throw Exception if error occurs
	* If token generated, redirect to customer login screen
 	* 
	*/
	public function processOrder($transaction)
	{
		//Call API execute function.
		$orderApiResponse = $this->call->callCreateOrderApi($transaction);

		//If Token creation is successful, redirect user to Afterpay Lightbox.
		if(!$orderApiResponse)
		{
		    throw new AfterpayConfigurationException("Something went wrong in connection");
		}else{
		    $redirectURL = $this->call->callRedirect($orderApiResponse);
		    header('Location: '.$redirectURL);
		}
		exit;
		
	}

}
