<?php

error_reporting(E_ALL); ini_set('display_errors', 1);
include('../Afterpay/Api/ProcessRefund.php');
include('../Afterpay/Api/Amount.php');

use Afterpay\Api\ProcessRefund;
use Afterpay\Api\Amount;

//Capture Payment ID or provide the payment Id to be refunded.
$paymentId = $_GET['paymentId'];

if(!$paymentId || $paymentId == '')
{
	echo "Please provide correct Payment Id to process Refund";
	exit;
}
else
{
	//Pass Amount and currency to intiate the refund.
	$refundAmount = new Amount('5.00','AUD');
	$processRefundObj = new ProcessRefund();
	// $refundAmountObj = $processRefundObj->setAmount($refundAmount);
	//Call processOrderRefund function from Process Refund class to call Refund API
	$processRefundObj = $processRefundObj->processOrderRefund($paymentId,$refundAmount);
	// If 
	if(isset($processRefundObj->refundId))
	{
		echo "Refund Transaction is successfull.";
	}
	else
	{
		var_dump($processRefundObj);
	}

}

?>