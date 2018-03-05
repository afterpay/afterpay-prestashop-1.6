<?php

namespace Afterpay\Api;

require_once(dirname(__FILE__) . "/../Core/Call.php");

use Afterpay\Core\Call;

/**
	* Class CapturePayment
	*
	* Used to run Capture Payment API, once pre authorization is approved
	*
	* @package Afterpay\Api
*/

class CapturePayment
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

	/* Process Capture Payment
	*
	* @param Array Create Order Response
	* @param String Merchant Reference Id
	* 
 	* @return Capture Payment API Response
	*/
	public function processCapturePayment($apiResponse,$merchantReference = NULL)
	{ 
		$paymentObj = array('token' => $apiResponse['orderToken'],'merchantReference' => $merchantReference);

		$paymentApiResponse = $this->call->callCapturePaymentApi($paymentObj);
		return $paymentApiResponse;
	}
}
