<?php

include('../Afterpay/Api/Authorization.php');

use Afterpay\Api\Authorization;

/* 
* Validate Merchant credentials (ID and Secret Key)
* Set all Credentials in Core/AfterpayConfig Class
* Display the response of Pre Authorization API
*/

$preAuthObject = new Authorization();
$apiResponse = $preAuthObject->checkPreAuth();

var_dump($apiResponse);


//You can store minimum, maximum values into the database
//Later on amount validations can be added for minimum and maximum order limits

?>
