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
require_once( _PS_MODULE_DIR_ . 'afterpay/classes/AfterpayCheckout.php' );

class AfterpayValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess() {

        $afterpay_merchant_id = strval(Configuration::get('AFTERPAY_MERCHANT_ID'));
        $afterpay_merchant_key = strval(Configuration::get('AFTERPAY_MERCHANT_KEY'));
        $afterpay_api_environment = strval(Configuration::get('AFTERPAY_API_ENVIRONMENT'));

        $cart = $this->context->cart;
        $currency_code = $this->context->currency->iso_code;

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'afterpay') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            $this->_checkoutErrorRedirect('This payment method is not available. Please contact website administrator.');
        }

        // $params = $_REQUEST;

        // $this->context->smarty->assign([
        //     'params' => $params,
        // ]);


        $afterpay_checkout =    new AfterpayCheckout($afterpay_merchant_id, 
                                    $afterpay_merchant_key, 
                                    $afterpay_api_environment, 
                                    $cart,
                                    $currency_code
                                    // $_REQUEST["user_agent"]
                                );

        try{
            $afterpay_checkout->createOrderToken();
        }
        catch( Exception $e ) {

            $log_message = json_encode($e->getMessage());
            $log_message = "Afterpay Token Generation Failure: " . $log_message . " Payload: " . preg_replace( "/\r|\n/", "", print_r($afterpay_checkout->_extractPayload(), true) );
            PrestaShopLogger::addLog($log_message, 3, NULL, "Afterpay", 1);

            $this->_checkoutErrorRedirect( "Afterpay Token Generation Failure. Please contact Website Administrator" );
        }

        $this->setTemplate('module:afterpay/views/templates/front/payment_return.tpl');
    }

    private function _checkoutErrorRedirect($message) {

        if( !empty($message) ) {
            $this->errors[] = $message;
        }
        $link = $this->context->link->getModuleLink('afterpay','payment', array("afterpay_error" => $message) );
        Tools::redirect($link); 
    }
}