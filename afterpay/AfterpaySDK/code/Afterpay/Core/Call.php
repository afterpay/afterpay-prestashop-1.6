<?php

namespace Afterpay\Core;

require_once(dirname(__FILE__) . "/../Exception/AfterpayConfigurationException.php");
require_once(dirname(__FILE__) . '/AfterpayConfig.php');

use Afterpay\Exception\AfterpayConfigurationException;	
use Afterpay\Core\AfterpayConfig;	


/**
* Class Call
* Make all API calls and send back the response
* Includes API execute function
*
* @package Afterpay\Core
*/

class Call
{   

    /**
    * Afterpay Merchant ID (Sandbox or Production)
    */    
    protected $merchantId;
    /**
    * Afterpay Merchant SecretKey (Sandbox or Production)
    */
	protected $merchantSecretKey;
	/**
    * Afterpay API Config
    */
	protected $apiConfigObj;
	/**
	* Default Constructor
	*
	* 
	* Assign values for Merchant ID and Secret Key
	* @param Array $apiParams 
 	* 
	*/
	public function __construct( $input = NULL )
	{
		//Set Config Parameters.
		$this->apiConfigObj = new AfterpayConfig( $input );
		$this->merchantId = $this->apiConfigObj->getMerchantId();
		$this->merchantSecretKey = $this->apiConfigObj->getMerchantSecret();
	}

	/**
	* Function to call Config API
	* @return Array Api Response
 	* 
	*/
	public function callAuthorizationApi()
	{
		
		$curlURL = $this->apiConfigObj->getConfigUrl();	
		$curlResponse = $this->execute($curlURL,'GET');
		return $curlResponse;
	}

	/**
	* Function to call Create Order API
	* @return Array Api Response
 	* 
	*/
	public function callCreateOrderApi($orderObject)
	{
		$curlURL = $this->apiConfigObj->getOrderUrl();
		$curlResponse = $this->execute($curlURL,'POST',$orderObject);
		return $curlResponse;
	}

	/**
	* Function to call Get Order API
	* @param Order Token
	* @return Array Api Response
 	* 
	*/
	public function callGetOrderApi($orderToken)
	{
		$curlURL = $this->apiConfigObj->getOrderUrl();
		
		//Pass Afterpay Order Token
		$curlURL = $curlURL."/".$orderToken;
		$curlResponse = $this->execute($curlURL,'GET');
		return $curlResponse;
	}

	/**
	* Function to call Capture Payment API
	* @return Array Api Response
 	* 
	*/
	public function callCapturePaymentApi($paymentObject)
	{
		$curlURL = $this->apiConfigObj->getPaymentUrl();
		$curlResponse = $this->execute($curlURL,'POST',$paymentObject);
		return $curlResponse;
	}

	/**
	* Function to call process create refund
	* @return Array Api Response
 	* 
	*/
	public function callCreateRefundApi($paymentId,$refundObj)
	{

		$curlURL = $this->apiConfigObj->getRefundUrl();

		$curlURL = $curlURL."/".$paymentId."/refund";

		$curlResponse = $this->execute($curlURL,'POST',$refundObj);
		return $curlResponse;
	}

	/**
	* Execute API Request
	* @param string $curlURL
	* @param string $curlMethod
	* @param Object $dataObject
	* @throws AfterpayConfigurationException
	*
	* @return array
 	*/

	Protected function execute($curlURL,$curlMethod,$dataObject ='')
	{
		//Check if CURL module exists. 
		if (!function_exists("curl_init")) {
 			throw new AfterpayConfigurationException("Curl module is not available on this system");
 		}
 		try {
			//Curl Implementation

			// create a new cURL resource
			$ch = curl_init();

			//Call fucntion to create CURL Headers

			$curlHeaders = $this->createHeaders();

			//Call CURL URL
			curl_setopt($ch, CURLOPT_URL, $curlURL);
			curl_setopt($ch, CURLOPT_TIMEOUT,80); // Set timeout to 80s			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders); //Pass CURL HEADERS
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //Do not output response on screen
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $curlMethod);  
			if($dataObject != ''){
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataObject, JSON_UNESCAPED_SLASHES));   
			}
			
			// grab URL and pass it to the browser
			$curlResponse = curl_exec($ch);
			$curlResponse = json_decode($curlResponse);

			if( empty($curlResponse) ) {
				throw new AfterpayConfigurationException("Invalid Response from Afterpay API");
			}
			
			// close cURL resource, and free up system resources
			curl_close($ch);

			return $curlResponse;
		}
		catch (Exception $e) {
			throw new AfterpayConfigurationException("Something went wrong in Afterpay API Connection");

		}

	}

	/**
	* Create CURL Headers
	* @throws AfterpayConfigurationException
	*
	* @return array
 	*/

 	Private function createHeaders($customHeaders = "")
 	{
 		$headers = array(
    			'Content-Type:application/json',
    			'Authorization: Basic '. base64_encode($this->merchantId.':'.$this->merchantSecretKey),
    			'User-Agent: '.$this->apiConfigObj->getSDKName().' ; '.$this->apiConfigObj->getSDKVersion() .'; PHP: '.phpversion().';'.$customHeaders
		);
		return $headers;
 	}

 	/**
	* Redirect to Afterpay Portal
	*
	* @param Object CreateOrder API Response
	* @throws AfterpayConfigurationException
	*
	* @return string Redirect URL
 	*/

 	Public function callRedirect($orderApiResponse)
 	{
 		//Check if Order Token exists
		if ( empty($orderApiResponse->token) || !$orderApiResponse->token ) {
			if( !empty($orderApiResponse->message) ) {
 				throw new AfterpayConfigurationException( $orderApiResponse->message . ": " . $orderApiResponse->errorCode. " (" . $orderApiResponse->errorId . ") " );
			}
			else {
 				throw new AfterpayConfigurationException( "Connection Error" );
			}
 		}
 		$redirectURL = $this->apiConfigObj->getCheckoutUrl();
 		//echo $redirectURL; echo $this->apiConfigObj->getCallbackUrl();exit;
 		$redirectURL = $redirectURL.$orderApiResponse->token.'&redirected=1';
 		
 		return $redirectURL;	
 	}
 	
}