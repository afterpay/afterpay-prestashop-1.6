<?php

include('../Afterpay/Api/CapturePayment.php');
include('../Afterpay/Validation/PriceValidator.php');

use Afterpay\Api\CapturePayment;
use Afterpay\Core\Call;
use Afterpay\Validation\PriceValidator;

//Capture Order Status and Order Token. Need to pass Create Order Response (status & orderToken params)
$apiResponse = $_GET;

//If API get response is not valid, throw ar error.
if(!$apiResponse)
{
	echo "Api Response is not valid";
	exit;

}

if($apiResponse['status'] == "CANCELLED")
{
	var_dump($apiResponse);
	exit;
}

//Call Get Order API to get the order Total amount.
if($apiResponse['status'] == "SUCCESS")
{
	$callObject = new Call();
	$getOrderResponse = $callObject->callGetOrderApi($apiResponse['orderToken']);
}
//Set this Amount with Current Cart Amount.
$cartAmount = '10.00';  

//Compare Afterpay Order Amount with current Cart Amount.
//Need to pass this value from current cart. Need to validate if cart amount has been changed by the user

$priceValidator = new PriceValidator();
$priceValidatorResponse = $priceValidator->validateCartTotal($getOrderResponse,$cartAmount);

//Merchant Reference Id or Order Id. Please pass this value from your system.
$merchantReferenceId = 'merchantOrder-123433333';

//If Order Status is confirmed and cart amount has not been changed, called capturePayment API
if($apiResponse['status'] == "SUCCESS")
{
	$capturePaymentObj = new CapturePayment();
	$capturePaymentResponse = $capturePaymentObj->processCapturePayment($apiResponse,$merchantReferenceId);
}

if($capturePaymentResponse->status == "APPROVED")
{
	echo('<h2>Order Id '.$capturePaymentResponse->id.' is successfully placed with Afterpay');			
}
else
{
	//Display the error response
	var_dump($capturePaymentResponse);
	exit;
}
//Store Payment Id in database for future reference

$paymentId = $capturePaymentResponse->id;

//Process Refund if required
echo '<br><a href="http://php-docker.local:8080/SampleCode/SampleProcessRefund.php?paymentId='.$paymentId.'">Process Refund</a>';

?>