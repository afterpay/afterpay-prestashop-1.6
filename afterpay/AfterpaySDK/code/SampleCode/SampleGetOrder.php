<?php

include('../Afterpay/Core/Call.php');

use Afterpay\Core\Call;

//Provided Valid Order Token (not expired) from Create Order API response.
$orderToken = "h4a19so0pinck1b7e2f2mqjl0706q6gguleutr90rg4cse244g48";

//Call Get Order API to get the order Total amount.
$callObject = new Call();
$getOrderResponse = $callObject->callGetOrderApi($orderToken);

var_dump($getOrderResponse);
