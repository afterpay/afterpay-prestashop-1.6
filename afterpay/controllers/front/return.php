<?php
/*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */
require_once( _PS_MODULE_DIR_ . 'afterpay/classes/AfterpayCapture.php' );

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class AfterpayReturnModuleFrontController extends ModuleFrontController
{

    private $afterpay_merchant_id;
    private $afterpay_merchant_key;
    private $afterpay_api_environment;
    private $afterpay_user_agent;
    private $params;

    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $this->params = $_REQUEST;
        $this->context->smarty->assign([
            "params" => $this->params,
        ]);

        $params = $_REQUEST;

        $validate_error = $this->_validateCredentials($params);

        if( count($validate_error) ) {
            $error["message"] = "Invalid Response: Missing Afterpay transaction " . implode($validate_error, ", ");
            $this->_checkoutErrorRedirect($error);
        }

        $this->_retrieveAfterpayConfiguration();

        $transaction_integrity = $this->_checkTransactionIntegrity();

        $results = $this->_doCapture();

        $this->setTemplate("module:afterpay/views/templates/front/payment_return.tpl");

    }

    private function _retrieveAfterpayConfiguration() {
        $this->afterpay_merchant_id     =   Configuration::get('AFTERPAY_MERCHANT_ID');
        $this->afterpay_merchant_key    =   Configuration::get('AFTERPAY_MERCHANT_KEY');
        $this->afterpay_api_environment =   Configuration::get('AFTERPAY_API_ENVIRONMENT');
        $this->afterpay_user_agent      =   Configuration::get('AFTERPAY_USER_AGENT');
    }

    private function _checkTransactionIntegrity() {

        $error  =   array(
                        "error" =>  false
                    );

        $afterpay_capture = new AfterpayCapture($this->afterpay_merchant_id, $this->afterpay_merchant_key, $this->afterpay_api_environment, $this->params, NULL, $this->afterpay_user_agent);
        $results = $afterpay_capture->doTransactionIntegrityChecking();



        if( empty($results) ) {
            $error["error"]     =   true;
            $error["message"]   =   "Afterpay Transaction Capture Failed - Incorrect Order Token Detected";
            
            $message = "Afterpay Transaction Integrity Check Error: " . $error['message'];
            PrestaShopLogger::addLog($message, 2, NULL, "Afterpay", 1);

        }
        else if( $results["error"] && !empty($results["message"]) ) {
            $error["error"]     =   true;
            $error["message"]   =   $results["message"];


            $log_message        =   "Afterpay Transaction Capture Failed; " .
                                    $results["message"] . " " . $results["info"];

            $message = "Afterpay Transaction Integrity Check Error: " . $log_message;
            PrestaShopLogger::addLog($message, 2, NULL, "Afterpay", 1);
        }
        else if( $results["results"]->totalAmount->amount != round($this->context->cart->getOrderTotal(), 2) ) {
            $error["error"]     =   true;
            $error["message"]   =   "Afterpay Transaction Capture Failed - Incorrect Amount Detected";


            $log_message        =   "Afterpay Transaction Capture Failed - Incorrect Amount Detected;" . 
                                    " API: " . $results["results"]->totalAmount->amount .
                                    " Session: " . round($this->context->cart->getOrderTotal(), 2);


            $message = "Afterpay Transaction Integrity Check Error: " . $log_message;
            PrestaShopLogger::addLog($message, 2, NULL, "Afterpay", 1);
        }
        // else if( $results["results"]->merchantReference != $this->context->cart->id ) {
        //     $error["error"]     =   true;
        //     $error["message"]   =   "Afterpay Transaction Capture Failed - Incorrect Merchant Reference Detected";


        //     $log_message        =   "Afterpay Transaction Capture Failed - Incorrect Merchant Reference Detected;" . 
        //                             " Token: " . $this->params["orderToken"] .
        //                             " API: " . $results["results"]->merchantReference .
        //                             " Session: " . $this->context->cart->id;
        // }

        if( !empty($error) && !empty($error["error"]) && $error["error"] ) {
            $this->_checkoutErrorRedirect($error);
        }
    }

    private function _doCapture() {
        $afterpay_capture = new AfterpayCapture($this->afterpay_merchant_id, $this->afterpay_merchant_key, $this->afterpay_api_environment, $this->params, NULL, $this->afterpay_user_agent);
        $results = $afterpay_capture->createCapturePayment();

        if( !empty($results) && !empty($results["error"]) && $results["error"] ) {

            $message = "Afterpay Transaction Capture Failed: " . $results["message"];


            $log_message    =   "Afterpay Transaction Capture Failed: " . $results["message"] . " " . $results["info"];
            
            PrestaShopLogger::addLog($log_message, 2, NULL, "Afterpay", 1);

            $this->_checkoutErrorRedirect($results);
        }
        else {
            $customer = new Customer($this->context->cart->id_customer);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$this->context->cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
        }

        return $results;
    }


    private function _validateCredentials($params) {
        $error = array();

        if( empty($params["orderToken"]) ) {
            $error[] = "token";
        }

        if( empty($params["status"]) ) {
            $error[] = "status";
        }

        return $error;
    }


    private function _checkoutErrorRedirect($results) {
        $link = $this->context->link->getModuleLink('afterpay','payment', array("afterpay_error" => $results["message"]) );
        Tools::redirect($link); 
    }
}
