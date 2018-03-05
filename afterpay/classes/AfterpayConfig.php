<?php
/*
* 2017 Afterpay Touch Group
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2017 Afterpay Touch Group
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Core/Call.php');

// use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Afterpay\Core\Call as AfterpayCall;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AfterpayConfig
{
    private $afterpay_call;
    private $enabled;
    private $merchant_id;
    private $merchant_secret;
    private $mode;

    public function __construct($merchant_id, $merchant_secret, $mode, $enabled = false, $user_agent) {
        $config         =   array(
                                'merchantId'        =>  $merchant_id,
                                'merchantSecret'    =>  $merchant_secret,
                                'mode'              =>  $mode,
                                'sdkName'           =>  $user_agent
                            );

        $this->merchant_id      =   $merchant_id;
        $this->merchant_secret  =   $merchant_secret;
        $this->mode             =   $mode;

        $this->afterpay_call    =   new AfterpayCall($config);

        $this->enabled = $enabled;
    }

    public function _update_payment_limits(){

        $return = array( "error" => false );

        if($this->enabled) {

            $results = $this->afterpay_call->callAuthorizationApi();

            if( empty($results) || !empty($results->errorCode) ) {
                $return["error"]   =    true;
                $return["message"] =    $results->message;

                $masked_merchant_secret = substr($this->merchant_secret, 0, 4) . '****' . substr($this->merchant_secret, -4);

                //log the error
                $log_message       =    "Afterpay Credentials Error: " . $results->errorCode . 
                                        " (" . $results->errorId . ") " . 
                                        " " . $results->message .
                                        " ID: " . $this->merchant_id .
                                        " Key: " . $masked_merchant_secret;

                PrestaShopLogger::addLog($log_message, 2, NULL, "Afterpay", 1);

                //nullify the payment limits values
                Configuration::updateValue('AFTERPAY_PAYMENT_MAX', 0);
                Configuration::updateValue('AFTERPAY_PAYMENT_MIN', 0);

                return $return;   
            }

            if( !empty($results[0]->maximumAmount->amount) ) {
                Configuration::updateValue('AFTERPAY_PAYMENT_MAX', $results[0]->maximumAmount->amount);
            }
            else {
                Configuration::updateValue('AFTERPAY_PAYMENT_MAX', 0);
            }

            if( !empty($results[0]->minimumAmount->amount) ) {
                Configuration::updateValue('AFTERPAY_PAYMENT_MIN', $results[0]->minimumAmount->amount);
            }
            else {
                Configuration::updateValue('AFTERPAY_PAYMENT_MIN', 0);
            }
        }

        return $return;
    }
}