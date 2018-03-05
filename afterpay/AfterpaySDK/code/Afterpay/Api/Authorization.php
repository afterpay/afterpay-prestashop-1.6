<?php

namespace Afterpay\Api;

require_once(dirname(__FILE__) . "/../Core/Call.php");

use Afterpay\Core\Call;

/**
* Class Authorization
*
* Validate Merchant Id and Secret Key Credentials
*
* @package Afterpay\Api
*/

class Authorization
{
	/**
	* Check Pre Authorization details.
	* 
 	* Call Authorization API
 	* @return Config Api Response
	*/
	public function checkPreAuth()
	{
		$apiObject = new Call();
		$preAuthApiResponse = $apiObject->callAuthorizationApi();
		return $preAuthApiResponse;
		
	}
}
