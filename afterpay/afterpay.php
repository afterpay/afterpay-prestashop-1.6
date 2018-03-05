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

require_once( _PS_MODULE_DIR_ . 'afterpay/classes/AfterpayConfig.php');
require_once( _PS_MODULE_DIR_ . 'afterpay/classes/AfterpayRefund.php');

// use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}


/**
 * Class Afterpay
 *
 * Base Class for the entire Afterpay PrestaShop Module
 * Utilise Afterpay API V1
 */
class Afterpay extends PaymentModule
{
    protected $_html = '';
    protected $_postErrors = array();

    public $afterpay_merchant_id;
    public $afterpay_merchant_key;
    public $afterpay_api_environment;
    public $afterpay_enabled;
    public $afterpay_payment_min;
    public $afterpay_payment_max;

    /**
     * Constructor function
     * Set up the Module details and initial configurations
     * since 1.0.0
     */
    public function __construct()
    {
        $this->name = 'afterpay';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->author = 'Afterpay Touch Group';
        $this->controllers = array('validation', 'return', 'payment');
        $this->is_eu_compatible = 0;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->_init_configurations();

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Afterpay Payment Gateway');
        $this->description = $this->l('This is a payment gateway module for Afterpay');

        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
    }
    
    /**
     * Install function
     * Set up the Module Hooks
     * since 1.0.0
     */
    public function install() {

        if  (!parent::install() || !$this->registerHook('payment') || !$this->registerHook('displayPaymentEU') || !$this->registerHook('paymentReturn') 
                || !$this->registerHook('actionOrderStatusUpdate') 
                || !$this->registerHook('actionProductCancel') 
                || !$this->registerHook('actionOrderSlipAdd') 
                || !$this->registerHook('displayProductPriceBlock') 
                || !$this->registerHook('displayFooterProduct') 
                || !$this->registerHook('displayHeader')
                || !$this->registerHook('displayAdminOrder') 
            ) {
            return false;
        }
        return true;
    }
    
    /**
     * Main Hook for Payment Module
     * Set up the Module Hooks
     * @param array $params
     * @return array
     * since 1.0.0
     */
    public function hookPaymentOptions($params) {
        if (!$this->afterpay_enabled) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $total = $this->context->cart->getOrderTotal();
        $payment_limits = $this->_getFrontEndLimits();

        if( $payment_limits["afterpay_payment_min"] > $total ||  
            $payment_limits["afterpay_payment_max"] < $total) {
            
            return;
        }

        $payment_options = [
            // $this->getOfflinePaymentOption(),
            $this->getExternalPaymentOption(),
            // $this->getEmbeddedPaymentOption(),
            // $this->getIframePaymentOption(),
        ];

        return $payment_options;
    }
    
    /**
     * Main Function to output Afterpay in the checkout
     * Set up the Module Hooks
     * @return PaymentOption
     * since 1.0.0
     */
    public function hookPayment($params) {
        if (!$this->afterpay_enabled) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $total = $this->context->cart->getOrderTotal();
        $payment_limits = $this->_getFrontEndLimits();

        if( $payment_limits["afterpay_payment_min"] > $total ||  
            $payment_limits["afterpay_payment_max"] < $total) {
            
            return;
        }

        // $externalOption = new PaymentOption();

        return $this->context->smarty->fetch( __DIR__ . "/views/templates/front/payment.tpl");
    }

    public function hookDisplayPaymentEU($params) {
        if (!$this->afterpay_enabled) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $total = $this->context->cart->getOrderTotal();
        $payment_limits = $this->_getFrontEndLimits();

        if( $payment_limits["afterpay_payment_min"] > $total ||  
            $payment_limits["afterpay_payment_max"] < $total) {
            
            return;
        }

        $payment_options = array(
            'cta_text'  => $this->l('Pay with Afterpay'),
            'logo'      => Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/images/payment_checkout.png'),
            'action'    => $this->context->link->getModuleLink($this->name, 'validation', array(), true)
        );

        return $payment_options;
    }


    /**
     * Form generation function
     *
     * @return bool
     * since 1.0.0
     */
    protected function generateForm()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = sprintf("%02d", $i);
        }

        $years = [];
        for ($i = 0; $i <= 10; $i++) {
            $years[] = date('Y', strtotime('+'.$i.' years'));
        }

        $this->context->smarty->assign([
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true),
            'months' => $months,
            'years' => $years,
        ]);

        return false;
    }


    /*-----------------------------------------------------------------------------------------------------------------------
                                                        Afterpay Configurations
    -----------------------------------------------------------------------------------------------------------------------*/

    /**
     * Initialise the configuration values
     * since 1.0.0
     */
    private function _init_configurations() {

        $config = Configuration::getMultiple(array('AFTERPAY_ENABLED', 'AFTERPAY_MERCHANT_ID', 'AFTERPAY_MERCHANT_KEY', 'AFTERPAY_API_ENVIRONMENT', 'AFTERPAY_USER_AGENT'));
        if (!empty($config['AFTERPAY_ENABLED'])) {
            $this->afterpay_enabled = $config['AFTERPAY_ENABLED'];
        }
        if (!empty($config['AFTERPAY_MERCHANT_ID'])) {
            $this->afterpay_merchant_id = $config['AFTERPAY_MERCHANT_ID'];
        }
        if (!empty($config['AFTERPAY_MERCHANT_KEY'])) {
            $this->afterpay_merchant_key = $config['AFTERPAY_MERCHANT_KEY'];
        }
        if (!empty($config['AFTERPAY_API_ENVIRONMENT'])) {
            $this->afterpay_api_environment = $config['AFTERPAY_API_ENVIRONMENT'];
        }
        if (!empty($config['AFTERPAY_PAYMENT_MIN'])) {
            $this->afterpay_payment_min = $config['AFTERPAY_PAYMENT_MIN'];
        }
        if (!empty($config['AFTERPAY_PAYMENT_MAX'])) {
            $this->afterpay_payment_max = $config['AFTERPAY_PAYMENT_MAX'];
        }
        if (!empty($config['AFTERPAY_USER_AGENT'])) {
            $this->afterpay_user_agent = $config['AFTERPAY_USER_AGENT'];
        }
    }

    /**
    * getContent() is required to show the "Configuration Page" option on Module Page
    * @return string
    * since 1.0.0
    */
    public function getContent() {
        $output = null;
     
        if (Tools::isSubmit('submit'.$this->name)) {
            $output = $this->_validate_configuration();
        }
        
        return $output . $this->displayForm();
    }


    /**
    * Validating the Configuration Form and append the output
    * @return string
    * since 1.0.0
    */
    private function _validate_configuration() {

        $afterpay_merchant_id = strval(Tools::getValue('AFTERPAY_MERCHANT_ID'));
        $afterpay_merchant_key = strval(Tools::getValue('AFTERPAY_MERCHANT_KEY'));
        $afterpay_api_environment = strval(Tools::getValue('AFTERPAY_API_ENVIRONMENT'));
        $afterpay_enabled = strval(Tools::getValue('AFTERPAY_ENABLED'));

        $error = false;

        $output = "";

        //validate Afterpay Enabled
        if (empty($afterpay_enabled) ) {

            $output .= $this->displayWarning($this->l('Afterpay is Disabled'));
        }
        
        Configuration::updateValue('AFTERPAY_ENABLED', $afterpay_enabled);

        //validate Merchant ID
        if (!$afterpay_merchant_id
            || empty($afterpay_merchant_id)
            || !Validate::isGenericName($afterpay_merchant_id)) {

            $output .= $this->displayError($this->l('Invalid Merchant ID value'));
            $error = true;
        }
        else {
            Configuration::updateValue('AFTERPAY_MERCHANT_ID', $afterpay_merchant_id);
        }

        //validate Merchant Key
        if (!$afterpay_merchant_key
            || empty($afterpay_merchant_key)
            || !Validate::isGenericName($afterpay_merchant_key)) {

            $output .= $this->displayError($this->l('Invalid Merchant Key value'));
            $error = true;
        }
        else {
            Configuration::updateValue('AFTERPAY_MERCHANT_KEY', $afterpay_merchant_key);
        }

        //validate API Environment
        if (empty($afterpay_api_environment)) {

            $output .= $this->displayError($this->l('Invalid Api Environment value'));
            $error = true;
        }
        else {
            Configuration::updateValue('AFTERPAY_API_ENVIRONMENT', $afterpay_api_environment);
        }

        if( !empty($afterpay_merchant_id) && !empty($afterpay_merchant_key)  && !empty($afterpay_api_environment) ) {

            $user_agent       =   "AfterpayPrestaShop1.6Module " . $this->version . " - Merchant ID: " . $afterpay_merchant_id . " - PrestaShop " . _PS_VERSION_ . " - URL: " . Tools::getHttpHost(true) . __PS_BASE_URI__;
            
            Configuration::updateValue('AFTERPAY_USER_AGENT', $user_agent);

            $afterpay_admin         =   new AfterpayConfig(
                                            $afterpay_merchant_id, 
                                            $afterpay_merchant_key, 
                                            $afterpay_api_environment, 
                                            $afterpay_enabled,
                                            $user_agent
                                        );
            $payment_limits_check   =   $afterpay_admin->_update_payment_limits();
        }

        if( $payment_limits_check["error"] ) {
            $output .= $this->displayError($this->l( $payment_limits_check["message"] ));
        }
        else if( !$error ) {
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output;
    }

    /**
    * DisplayFrom() is required to show the "Configuration Form Page"
    * @return string
    * since 1.0.0
    */
    public function displayForm() {

        // Get default language
        $afterpay_merchant_id       =   (int)Configuration::get('AFTERPAY_MERCHANT_ID');
        $afterpay_merchant_key      =   Configuration::get('AFTERPAY_MERCHANT_KEY');
        $afterpay_api_environment   =   (int)Configuration::get('AFTERPAY_API_ENVIRONMENT');
        $afterpay_enabled           =   (int)Configuration::get('AFTERPAY_ENABLED');
        $afterpay_payment_min       =   (int)Configuration::get('AFTERPAY_PAYMENT_MIN');
        $afterpay_payment_max       =   (int)Configuration::get('AFTERPAY_PAYMENT_MAX');
         
        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend'    => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type'      =>  'select',
                    'label'     =>  $this->l('Enabled'),
                    'name'      =>  'AFTERPAY_ENABLED',
                    'options'   =>  array(
                                        'query' =>  array(
                                                        array(
                                                            'enabled'       =>  false,
                                                            'enabled_name'  =>  'No'
                                                        ),
                                                        array(
                                                            'enabled'       =>  true,
                                                            'enabled_name'  =>  'Yes'
                                                        )
                                                    ),
                                        'id'    => 'enabled',
                                        'name'  => 'enabled_name'
                                    ),
                    'required'  =>  true
                ),
                array(
                    'type'      =>  'text',
                    'label'     =>  $this->l('Merchant ID'),
                    'name'      =>  'AFTERPAY_MERCHANT_ID',
                    'size'      =>  5,
                    'required'  =>  true
                ),
                array(
                    'type'      =>  'text',
                    'label'     =>  $this->l('Merchant Key'),
                    'name'      =>  'AFTERPAY_MERCHANT_KEY',
                    'size'      =>  128,
                    'required'  =>  true
                ),
                array(
                    'type'      =>  'select',
                    'label'     =>  $this->l('API Environment'),
                    'name'      =>  'AFTERPAY_API_ENVIRONMENT',
                    'options'   =>  array(
                                        'query' =>  array(
                                                        array(
                                                            'api_mode'  =>  'sandbox',
                                                            'api_name'  =>  'Sandbox'
                                                        ),
                                                        array(
                                                            'api_mode'  =>  'production',
                                                            'api_name'  =>  'Production'
                                                        )
                                                    ),
                                        'id'    => 'api_mode',
                                        'name'  => 'api_name'
                                    ),
                    'required'  =>  true
                ),
                array(
                    'type'      =>  'text',
                    'label'     =>  $this->l('Min Payment Limit'),
                    'name'      =>  'AFTERPAY_PAYMENT_MIN',
                    'size'      =>  128,
                    'readonly'  =>  'readonly'
                ),
                array(
                    'type'      =>  'text',
                    'label'     =>  $this->l('Max Payment Limit'),
                    'name'      =>  'AFTERPAY_PAYMENT_MAX',
                    'size'      =>  128,
                    'readonly'  =>  'readonly'
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
         
        $helper = new HelperForm();
         
        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
         
         
        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );
         
        // Load current value
        $helper->fields_value['AFTERPAY_ENABLED']           =   Configuration::get('AFTERPAY_ENABLED');
        $helper->fields_value['AFTERPAY_MERCHANT_ID']       =   Configuration::get('AFTERPAY_MERCHANT_ID');
        $helper->fields_value['AFTERPAY_MERCHANT_KEY']      =   Configuration::get('AFTERPAY_MERCHANT_KEY');
        $helper->fields_value['AFTERPAY_API_ENVIRONMENT']   =   Configuration::get('AFTERPAY_API_ENVIRONMENT');
        $helper->fields_value['AFTERPAY_PAYMENT_MIN']       =   Configuration::get('AFTERPAY_PAYMENT_MIN');
        $helper->fields_value['AFTERPAY_PAYMENT_MAX']       =   Configuration::get('AFTERPAY_PAYMENT_MAX');
         
        return $helper->generateForm($fields_form);
    }
    /*-----------------------------------------------------------------------------------------------------------------------
                                                    End of Afterpay Configurations
    -----------------------------------------------------------------------------------------------------------------------*/




    /*-----------------------------------------------------------------------------------------------------------------------
                                                    Start of Refund Codes
    -----------------------------------------------------------------------------------------------------------------------*/
    
    /**
    * Hook Action for Order Status Update (handles Refunds)
    * @param array $params
    * @return bool
    * since 1.0.0
    */
    public function hookActionOrderStatusUpdate($params) {

        if( !empty($params) && !empty($params['id_order']) ) {
            $order = new Order((int)$params['id_order']);
        }

        if( !empty($params) && !empty($params['newOrderStatus']) ) {
            $new_order_status = $params['newOrderStatus'];
        }

        if( $new_order_status->id == _PS_OS_REFUND_ ) {
            
            $afterpay_refund = $this->_constructRefundObject($order);

            //get the cart total since this would be Full Refund
            $cart = new Cart($order->id_cart);
            $order_total = round($cart->getOrderTotal(), 2);

            $payments = $order->getOrderPayments();
            $afterpay_transaction_id = $payments[0]->transaction_id;

            $currency = new CurrencyCore($order->id_currency);
            $currency_code = $currency->iso_code;

            $results = $afterpay_refund->doRefund($afterpay_transaction_id, $order_total, $currency_code);
            $this->_verifyRefund( $results );
            
        }
        return false;
    }
    
    /**
    * Hook Action for Partial Refunds
    * @param array $params
    * since 1.0.0
    */
    public function hookActionOrderSlipAdd($params) {

        if( !empty($params) && !empty($params["order"]->id) ) {
            $order = new Order((int)$params["order"]->id);

            $payments = $order->getOrderPayments();
            $afterpay_transaction_id = $payments[0]->transaction_id;

            $currency = new CurrencyCore($order->id_currency);
            $currency_code = $currency->iso_code;
        }

        
        $afterpay_refund = $this->_constructRefundObject($order);

        $refund_products_list   =   $params["productList"];
        $refund_total_amount    =   0;

        foreach( $refund_products_list as $key => $item ) {
            $refund_total_amount    +=  $item["amount"];
        }

        $refund_total_amount = round($refund_total_amount, 2);

        $results = $afterpay_refund->doRefund($afterpay_transaction_id, round($refund_total_amount, 2), $currency_code);

        if( !empty($results->errorCode) ) {

            $message = "Afterpay Partial Refund Error: " . $results->errorCode . " (" . $results->errorId . ") " . $results->message;
            PrestaShopLogger::addLog($message, 2, NULL, "Afterpay", 1);
        }
    }

    /**
    * Construct the Refunds Object based on the configuration and Refunds type
    * @param int $order
    * @return AfterpayRefund
    * since 1.0.0
    */
    private function _constructRefundObject($order) {
        $config = Configuration::getMultiple(array('AFTERPAY_MERCHANT_ID', 'AFTERPAY_MERCHANT_KEY', 'AFTERPAY_API_ENVIRONMENT'));
        
        $afterpay_merchant_id       =   (int)Configuration::get('AFTERPAY_MERCHANT_ID');
        $afterpay_merchant_key      =   Configuration::get('AFTERPAY_MERCHANT_KEY');
        $afterpay_api_environment   =   Configuration::get('AFTERPAY_API_ENVIRONMENT');
        $afterpay_user_agent        =   Configuration::get('AFTERPAY_USER_AGENT');

        $afterpay_refund    =   new AfterpayRefund(
                                    $afterpay_merchant_id, 
                                    $afterpay_merchant_key, 
                                    $afterpay_api_environment,
                                    $afterpay_user_agent
                                );

        return $afterpay_refund;
    }

    /**
    * Verify the Refunds results
    * @param string $results
    * since 1.0.0
    */
    private function _verifyRefund( $results ) {
        $refund_error = false;
        if( empty($results) ) {
            $refund_error   =   true;
            $error_message  =   "Missing refund response.";
        }
        if( !empty($results->errorCode) ) {
            $refund_error   =   true;
            $error_message  =   $results->message;
        }

        if( $refund_error ) {
            /*the order update doesn't work, hence the die()*/
            // $new_history = new OrderHistory();
            // $new_history->id_order = (int)$order->id;
            // $new_history->changeIdOrderState(1, $order, true);

            $return_url = $_SERVER['HTTP_REFERER'];

            echo $results->message . " " . $results->errorId;
            echo "<br/><a href='" . $return_url . "'>Return to Order Details</a>";

            $message = "Afterpay Full Refund Error: " . $results->errorCode . " (" . $results->errorId . ") " . $results->message;
            PrestaShopLogger::addLog($message, 2, NULL, "Afterpay", 1);

            die();
        }
    }

    /*-----------------------------------------------------------------------------------------------------------------------
                                                    End of Refund Codes
    -----------------------------------------------------------------------------------------------------------------------*/


    /*-----------------------------------------------------------------------------------------------------------------------
                                                    Afterpay Product Display
    -----------------------------------------------------------------------------------------------------------------------*/

    /**
    * Function to display the Afterpay Product Price Payment Breakdown
    * @param array $params
    * @return TPL
    * since 1.0.0
    */
    public function hookDisplayProductPriceBlock($params) {

        $current_controller = Tools::getValue('controller');
        $payment_limits = $this->_getFrontEndLimits();

        //note the different params between PrestaShop 1.7 and 1.6 (price and price_amount)
        if( $current_controller == "product" && 
            $params["type"] == "after_price" &&  
            $payment_limits["afterpay_payment_min"] <= $params["product"]->price &&  
            $payment_limits["afterpay_payment_max"] >= $params["product"]->price &&  
            $payment_limits["afterpay_enabled"] ) {

            $tax_rate       =   $params["product"]->tax_rate;
            $base_price     =   $params["product"]->base_price;
            $product_price  =   round($base_price + ( $base_price * $tax_rate / 100), 2, PHP_ROUND_HALF_UP);

            // foreach( $params["product"] as $key => $item ) {
            //     echo( "<h1>" . $key . "</h1>");
            //     var_dump($item);
            // }
            
            $this->context->smarty->assign( "afterpay_instalment_breakdown", $this->_getCurrentInstalmentsDisplay( $product_price ) );

            return $this->context->smarty->fetch( __DIR__ . "/views/templates/front/product_page.tpl");
        }
    }

    /**
    * Function to display the Afterpay Product Price Modal Window
    * @param array $params
    * @return TPL
    * since 1.0.0
    */
    public function hookDisplayFooterProduct($params) {

        $current_controller = Tools::getValue('controller');
        $payment_limits = $this->_getFrontEndLimits();

        if( $current_controller == "product"  && 
            $payment_limits["afterpay_payment_min"] <= $params["product"]->price &&  
            $payment_limits["afterpay_payment_max"] >= $params["product"]->price &&  
            $payment_limits["afterpay_enabled"] ) {

            return $this->context->smarty->fetch( __DIR__ . "/views/templates/front/product_modal.tpl");
        }
    }


    /**
    * Function to append Afterpay JS, CSS and Variables to Site Header
    * @param array $params
    * since 1.0.0
    */
    public function hookDisplayHeader($params) {
        $this->context->controller->addCSS($this->_path."css/afterpay.css", "all");
        $this->context->controller->addJS($this->_path."js/afterpay.js");

        $this->context->smarty->assign( "afterpay_base_url", Context::getContext()->shop->getBaseURL(true) );    
    }

    /*-----------------------------------------------------------------------------------------------------------------------
                                                End of Afterpay Product Display
    -----------------------------------------------------------------------------------------------------------------------*/

    /*-----------------------------------------------------------------------------------------------------------------------
                                                    Miscellaneous
    -----------------------------------------------------------------------------------------------------------------------*/
    
    /**
    * Function to get the Afterpay Front-End criteria 
    * @return array
    * since 1.0.0
    */
    private function _getFrontEndLimits() {

        $return["afterpay_enabled"]     =   (int)Configuration::get('AFTERPAY_ENABLED');
        $return["afterpay_payment_min"] =   (int)Configuration::get('AFTERPAY_PAYMENT_MIN');
        $return["afterpay_payment_max"] =   (int)Configuration::get('AFTERPAY_PAYMENT_MAX');

        return $return;
    } 

    /**
    * Function to check the Supported Currency
    * @param Cart $cart
    * @return bool
    * since 1.0.0
    */
    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function hookDisplayAdminOrder($params) {
        $order_id = $params["id_order"];
        $order = new Order( $order_id );

        if( $order->payment == $this->name ) {
            $this->context->controller->addCSS($this->_path."css/afterpay-admin.css", "all");
        }
    }

    /**
     * Display function for Total Payment
     *
     * @return string
     * since 1.0.0
     */
    private function _getCurrentCartTotalDisplay() {
        $cart = $this->context->cart;
        $order_total = round($cart->getOrderTotal(), 2);

        return $this->context->currency->iso_code . " " . $this->context->currency->sign . $order_total;
    }
    
    /**
     * Display function for Payment Breakdowns
     *
     * @return string
     * since 1.0.0
     */
    private function _getCurrentInstalmentsDisplay( $amount = NULL ) {

        if( empty($amount) ) {
            $cart = $this->context->cart;
            $amount = $cart->getOrderTotal();
        }
        $instalment = round($amount / 4, 2, PHP_ROUND_HALF_UP);

        return $this->context->currency->iso_code . " " . $this->context->currency->sign . 
            "<span class='afterpay-installments-value'>" . $instalment . "</span>";
    }
}