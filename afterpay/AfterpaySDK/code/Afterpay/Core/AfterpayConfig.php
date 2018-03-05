<?php

namespace Afterpay\Core;

/**
    * Class AfterpayConfig
    * Placeholder for Afterpay API Settings.
    *
    * @package Afterpay\Core
*/

class AfterpayConfig
{
    /**
    * SDK Name
    */
    protected $sdkName;
    /**
    * SDK Version
    */
    protected $sdkVersion;
    /**
    * Merchant Id
    */
    protected $merchantId;
    /**
    * Merchant Secret Key
    */
    protected $merchantSecret;
    /**
    * Afterpay Config URL
    */
    protected $configUrl;
    /**
    * Afterpay Order URL
    */
    protected $orderUrl;
    /**
    * Afterpay Checkout URL
    */
    protected $checkoutUrl;
    /**
    * Afterpay Payment URL
    */
    protected $paymentUrl;
    /**
    * Afterpay Refund URL
    */
    protected $refundUrl;
    /**
    * Afterpay Mode
    */
    protected $mode;


    public function __construct( $input = NULL ) {

        if( empty($input) ) {
            $filename = '../config.ini';
            //Check if all parameters are set properly in config.ini
            $validateConfigResponse = $this->validateConfigFile($filename);

            //If Config.ini is valid and all values are set properly.
            if ($validateConfigResponse)
            {
                //Set Config params, if mode is sandbox or production.
                $ini_array = parse_ini_file($filename, true);
                $this->setConfigParams($ini_array);
            }
            else
            {
                echo "Please check if config.ini exists and all values are set properly. Please make sure mode should be either sandbox or production";
                exit;
            }  

            $this->setEnvironment($ini_array['Service']['mode']);
        }
        else {

            if( !empty($input['mode']) ) {
                $this->setMode($input['mode']);
            }

            if( !empty($input['merchantId']) ) {
                $this->setMerchantId($input['merchantId']);
            }

            if( !empty($input['merchantSecret']) ) {
                $this->setMerchantSecret($input['merchantSecret']);
            }

            if( !empty($input['mode']) ) {
                $this->setEnvironment($input['mode']);
            }

            if( !empty($input['sdkName']) ) {
                $this->setSDKName($input['sdkName']);
            }

            if( !empty($input['sdkVersion']) ) {
                $this->setSDKVersion($input['sdkVersion']);
            }
        }
    }

    /*
    * Validate config.ini array and values
    * params config.ini file name $fileName
    * Returns boolean
    */
    protected function validateConfigFile($fileName)
    {
        //Check if config.ini file exists in the root directory
        if (!file_exists($fileName))
        {
            return false;
        }
        //Parse config.ini file to set configuration variables.
        $ini_array = parse_ini_file($fileName, true);

        //Check if Service mode exists. Can be either sandbox or production.
        if(!array_key_exists("Service",$ini_array))
        {
            return false;
        }
        //Check if Service is not sandbox or production
        if($ini_array['Service']['mode'] != "sandbox" && $ini_array['Service']['mode'] != "production")
        {
            return false;
        }
        //Check if Account Key exists
        if(!array_key_exists("Account",$ini_array))
        {
            return false;
        } 
        //Check if Merchant Id is blank
        if($ini_array['Account']['MerchantId'] == '')
        {
            return false;
        }
        //Check if Merchant Secret is blank
        if($ini_array['Account']['MerchantSecret'] == '')
        {
            return false;
        }
        //If all validations are correct, return true
        return true;
    }

    /**
    * Set Afterpay URL's based on the selected mode (sandbox or production)
    */
    protected function setConfigParams($configArray)
    {
        //Set Merchant Id, Secret Key and Callback Url
        $this->setMerchantId($configArray['Account']['MerchantId']);
        $this->setMerchantSecret($configArray['Account']['MerchantSecret']);
        $this->setSDKName('Afterpay-PHP-SDK');
        $this->setSDKVersion('1.0.1');
        $this->setCallbackUrl($configArray['Account']['CallbackUrl']);
    }

    /**
    * 
    */
    protected function setEnvironment($mode)
    {   
        //Set Merchant Id, Secret Key and Callback Url
        if($mode == 'sandbox')
        {
            $this->setConfigUrl('https://api-sandbox.afterpay.com/v1/configuration');
            $this->setOrderUrl('https://api-sandbox.afterpay.com/v1/orders');
            $this->setCheckoutUrl('https://portal-sandbox.afterpay.com/checkout?token=');
            $this->setPaymentUrl('https://api-sandbox.afterpay.com/v1/payments/capture');
            $this->setRefundUrl('https://api-sandbox.afterpay.com/v1/payments');

        }
        else if($mode == 'production')
        {
            $this->setConfigUrl('https://api.afterpay.com/v1/configuration');
            $this->setOrderUrl('https://api.afterpay.com/v1/orders');
            $this->setCheckoutUrl('https://portal.afterpay.com/checkout?token=');
            $this->setPaymentUrl('https://api.afterpay.com/v1/payments/capture');
            $this->setRefundUrl('https://api.afterpay.com/v1/payments');

        }

        return $this;
    }

    /**
    * Name of the SDK
    *
    * @param string $sdkName
    * 
    * @return $this
    */
    protected function setSDKName($sdkName)
    {
        $this->sdkName = $sdkName;
        return $this;
    }
     
    /**
    * Name of the SDK
    *
    * @return string
    */
    public function getSDKName()
    {
        return $this->sdkName;
    }
    /**
    * Version of the SDK
    *
    * @param string $sdkName
    * 
    * @return $this
    */
    protected function setSDKVersion($sdkVersion)
    {
        $this->sdkVersion = $sdkVersion;
        return $this;
    }    
    /**
    * Version of the SDK
    *
    * @return string
    */
    public function getSDKVersion()
    {
        return $this->sdkVersion;
    }
    /**
    * Merchant Id (Sandbox or Production)
    *
    * @param string $merchantId
    * 
    * @return $this
    */
    protected function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }    
    /**
    * Merchant Id
    *
    * @return string
    */
    public function getMerchantId()
    {
        return $this->merchantId;
    }
    /**
    * Merchant Secret Key (Sandbox or Production)
    *
    * @param string $merchantKey
    * 
    * @return $this
    */
    protected function setMerchantSecret($merchantSecret)
    {
        $this->merchantSecret = $merchantSecret;
        return $this;
    }    
    /**
    * Merchant Secret Key
    *
    * @return string
    */
    public function getMerchantSecret()
    {
        return $this->merchantSecret;
    }
    /**
    * Config URL (Sandbox or Production)
    *
    * @param string $configUrl
    * 
    * @return $this
    */
    protected function setConfigUrl($configUrl)
    {
        $this->configUrl = $configUrl;
        return $this;
    }    
    /**
    * Config URL
    *
    * @return string
    */
    public function getConfigUrl()
    {
        return $this->configUrl;
    }
    /**
    * Order URL (Sandbox or Production)
    *
    * @param string $orderUrl
    * 
    * @return $this
    */
    protected function setOrderUrl($orderUrl)
    {
        $this->orderUrl = $orderUrl;
        return $this;
    }    
    /**
    * API Mode
    *
    * @return string
    */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
    * Order URL
    *
    * @return string
    */
    public function getOrderUrl()
    {
        return $this->orderUrl;
    }
    /**
    * Checkout URL (Sandbox or Production)
    *
    * @param string $checkoutUrl
    * 
    * @return $this
    */
    protected function setCheckoutUrl($checkoutUrl)
    {
        $this->checkoutUrl = $checkoutUrl;
        return $this;
    }    
    /**
    * Checkout URL
    *
    * @return string
    */
    public function getCheckoutUrl()
    {
        return $this->checkoutUrl;
    }
    /**
    * Payment URL (Sandbox or Production)
    *
    * @param string $paymentUrl
    * 
    * @return $this
    */
    protected function setPaymentUrl($paymentUrl)
    {
        $this->paymentUrl = $paymentUrl;
        return $this;
    }    
    /**
    * Payment URL
    *
    * @return string
    */
    public function getPaymentUrl()
    {
        return $this->paymentUrl;
    }
    /**
    * Refund URL (Sandbox or Production)
    *
    * @param string $refundUrl
    * 
    * @return $this
    */
    protected function setRefundUrl($refundUrl)
    {
        $this->refundUrl = $refundUrl;
        return $this;
    }    
    /**
    * Refund URL
    *
    * @return string
    */
    public function getRefundUrl()
    {
        return $this->refundUrl;
    }
   
    /**
    * API Mode
    *
    * @return string
    */
    public function getMode()
    {
        return $this->mode;
    }

}