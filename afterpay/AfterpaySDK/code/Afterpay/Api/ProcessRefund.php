<?php

namespace Afterpay\Api;

require_once(dirname(__FILE__) . "/../Core/Call.php");

use Afterpay\Core\Call;

/**
	* Class ProcessRefund
	* Call Process Refund API 
	* @package Afterpay\Api
*/

class ProcessRefund
{
	/**
	* Process Order Refund
	*
	* @param String Payment Id
	* @param Object Order Response
 	* 
	*/
	protected $call;

	public function __construct( $input = NULL )
	{
		$this->call = new Call( $input );
	}

	public function processOrderRefund($paymentId,$amount)
	{
		$refundApiResponse = $this->call->callCreateRefundApi($paymentId, array("amount" => $amount) );
		return $refundApiResponse;
	}
	/**
	* Amount being collected.
	*
	* @param \Afterpay\Api\Amount $refundAmount
 	* 
 	* @return $this
	*/
	public function setAmount($amount)
 	{
		$this->amount = $amount;
		return $this;
	}
	/**
	* Amount being collected.
	*
	* @return \Afterpay\Api\Amount
	*/
	public function getAmount()
 	{
		return $this->amount;
 	}
}
