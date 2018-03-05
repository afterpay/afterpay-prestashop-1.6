<?php

//Capture Order Status and Order Token. Need to pass Create Order Response (status & orderToken params)
$apiResponse = $_GET;

//If API get response is not valid, throw ar error.
if(!$apiResponse)
{
	echo "Api Response is not valid";
	exit;

}


echo 'Your Afterpay order token has been created. Please click <a href="http://php-docker.local:8080/SampleCode/SampleCapturePayment.php?status=SUCCESS&orderToken='.$apiResponse["orderToken"].'">Capture Payment</a>';
