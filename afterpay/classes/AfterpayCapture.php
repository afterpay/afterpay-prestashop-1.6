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
*  @author Afterpay Touch Group <steven.gunarso@touchcorp.com>
*  @copyright  2017 Afterpay Touch Group
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/


require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/CapturePayment.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Core/Call.php');

// use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

use Afterpay\Api\CapturePayment as AfterpayCapturePayment;
use Afterpay\Core\Call as AfterpayCall;


if (!defined('_PS_VERSION_')) {
    exit;
}


/**
 * Class AfterpayCapture
 *
 * Afterpay PrestaShop Module API Capture class 
 * Utilise Afterpay API V1
 */
class AfterpayCapture
{
    private $afterpay_capture_payment;
    private $afterpay_call;
    private $params;
    private $merchant_reference;

    /**
    * Constructor Function
    *
    * @param string $merchant_id 
    * @param string $merchant_secret 
    * @param string $mode 
    * @param array $params 
    * @param string $merchant_reference
    * @return bool
    * since 1.0.0
    */
    public function __construct($merchant_id, $merchant_secret, $mode, $params, $merchant_reference = NULL, $user_agent) {
        $config         =   array(
                                'merchantId'        =>  $merchant_id,
                                'merchantSecret'    =>  $merchant_secret,
                                'mode'              =>  $mode,
                                'sdkName'           =>  $user_agent
                            );

        $this->afterpay_capture_payment = new AfterpayCapturePayment($config);
        $this->afterpay_call = new AfterpayCall($config);

        $this->params = $params;
        $this->merchant_reference = $merchant_reference;
    }
    
    /**
    * Transaction Integrity Checking function
    * Perform the API Call to check whether a transaction has been tampered with or not
    * @return array
    * since 1.0.0
    */
    public function doTransactionIntegrityChecking() {

        $results = $this->afterpay_call->callGetOrderApi( $this->params["orderToken"] );

        if( empty($results) ) {
            $return['message']   =   "No response received for the following token: " . $this->params["orderToken"] ;
            $return['info']      =   "";
            $return['error']     =   true;
        }
        else if( !empty( $results->errorCode ) ) {
            $return['message']   =   $results->message;
            $return['info']      =   "Code: " . $results->errorCode . " (" . $results->errorId . ") ";
            $return['error']     =   true;
        }
        else {
            //create the order on PrestaShop Admin
            $return['error']     =   false;
            $return['results']   =   $results;
        }

        return $return;
    }
    
    /**
    * Afterpay Capture function
    * Perform the API Call to capture a transaction
    * @return array
    * since 1.0.0
    */
    public function createCapturePayment() {
        $results = $this->afterpay_capture_payment->processCapturePayment($this->params);

        if( !empty( $results->status ) && $results->status == "APPROVED" && !empty($results->id) ) {
            //create the order on PrestaShop Admin
            $this->_createOrder($results->id);
            $return['error']     =   false;
        }
        else if( !empty( $results->errorCode ) ) {
            $return['message']   =   $results->message;
            $return['info']      =   "Code: " . $results->errorCode . " (" . $results->errorId . ") ";
            $return['error']     =   true;
        }
        else {
            $return['message']   =   "No response received for the following token: " . $this->params["orderToken"] ;
            $return['info']      =   "";
            $return['error']     =   true;
        }

        return $return;
    }
    
    /**
    * Create Order function
    * Create the PrestaShop Order after a successful Capture
    * @param string $afterpay_order_id
    * since 1.0.0
    */
    private function _createOrder($afterpay_order_id) {

        $cart = Context::getContext()->cart;

        $order_status = (int)Configuration::get("PS_OS_PAYMENT");

        $order_total = $cart->getOrderTotal(true, Cart::BOTH);


        $module = Module::getInstanceByName("afterpay");

        $extra_vars =   array(
                            "transaction_id"    =>  $afterpay_order_id
                        );

        $module->validateOrder($cart->id, $order_status, $order_total, "afterpay", null, $extra_vars, null, false, $cart->secure_key);

        $message = "Afterpay Order Captured Successfully - Order ID: " . $afterpay_order_id . "; PrestaShop Cart ID: " . $cart->id;
        PrestaShopLogger::addLog($message, 1, NULL, "Afterpay", 1);

    }
}