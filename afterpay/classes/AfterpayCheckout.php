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


require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/CreateOrder.php' );

require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Amount.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Transaction.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/RedirectUrls.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Consumer.php' );

require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Address.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/BillingAddress.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/ShippingAddress.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Item.php' );

require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Discount.php' );

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

use Afterpay\Api\CreateOrder as AfterpayCreateOrder;
use Afterpay\Api\Amount as AfterpayAmount;
use Afterpay\Api\Transaction as AfterpayTransaction;
use Afterpay\Api\RedirectUrls as AfterpayRedirectUrls;
use Afterpay\Api\Consumer as AfterpayCustomer;

use Afterpay\Api\Address as AfterpayAddress;
use Afterpay\Api\BillingAddress as AfterpayBillingAddress;
use Afterpay\Api\ShippingAddress as AfterpayShippingAddress;
use Afterpay\Api\Item as AfterpayItem;
use Afterpay\Api\Discount as AfterpayDiscount;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class AfterpayCheckout
 *
 * Afterpay PrestaShop Module API Order Token Generation Class
 * Utilise Afterpay API V1
 */
class AfterpayCheckout
{
    private $afterpay_create_order;
    private $enabled;
    private $cart_object;
    private $currency_code;
    private $transaction;

    /**
    * Constructor Function
    *
    * @param string $merchant_id 
    * @param string $merchant_secret 
    * @param string $mode 
    * @param Cart $cart_object 
    * @param string $currency_code
    * since 1.0.0
    */
    public function __construct($merchant_id, $merchant_secret, $mode, $cart_object, $currency_code = 'AUD') {

        $user_agent = Configuration::get('AFTERPAY_USER_AGENT');

        $config =   array(
                        'merchantId'        =>  $merchant_id,
                        'merchantSecret'    =>  $merchant_secret,
                        'mode'              =>  $mode,
                        'sdkName'           =>  $user_agent
                    );
        $this->afterpay_create_order = new AfterpayCreateOrder($config);

        $this->cart_object = $cart_object;
        $this->currency_code = $currency_code;

        $this->transaction = new AfterpayTransaction();
    }

    /**
    * Create Order Token function
    *
    * since 1.0.0
    */
    public function createOrderToken() {

        $this->_constructPayload();

        $result = $this->afterpay_create_order->processOrder($this->transaction);
    }

    /**
    * Construct API Order Token Payload function
    *
    * since 1.0.0
    */
    private function _constructPayload() {

        $this->transaction->setTotalAmount( $this->_processTotal( $this->currency_code ) ); 

        $this->transaction->setMerchantUrl( $this->_processRedirect() ); 

        $this->transaction->setConsumer( $this->_processCustomer() ); 
        
        // $this->transaction->setMerchantReference( $this->cart_object->id );

        $this->transaction->setBilling( $this->_processBillingAddress() );

        $this->transaction->setShipping( $this->_processShippingAddress() );

        $this->transaction->setItems( $this->_processItems() );

        //check for discounts
        $total_discounts = $this->cart_object->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
        if( !empty($total_discounts) ) {
            $this->transaction->setDiscounts( $this->_processDiscount( $total_discounts ) );
        }
    }


    /**
    * Extracting API Order Token Payload function for logging purposes
    *
    * since 1.0.0
    */
    public function _extractPayload() {
        $return = array();

        $return["billing"] = $this->transaction->getBilling();
        $return["item"] = $this->transaction->getItems();
        $return["total"] = $this->transaction->getTotalAmount();
        $return["consumer"] = $this->transaction->getConsumer();
        $return["discount"] = $this->transaction->getDiscounts();

        return $return;
    }

    /*----------------------------------------------------------------------------------------------------
                                            Item Processing Classes
    ----------------------------------------------------------------------------------------------------*/
    /**
    * Process the Order Items
    *
    * @return array
    * since 1.0.0
    */
    private function _processItems() {

        $products = $this->cart_object->getProducts(true);
        $items = array();

        foreach( $products as $key => $item ) {


            $product_price  =   round($item["price_wt"], 2);

            $item_amount    =   new AfterpayAmount( round( $product_price, 2), $this->currency_code );

            $product_item   =   new AfterpayItem( $item["name"], $item["reference"], $item["quantity"], $item_amount);
            $items[]        =   $product_item;
        }

        return $items;
    }
    
    /**
    * Process the Order Shipping Address
    *
    * @return array
    * since 1.0.0
    */
    private function _processShippingAddress() {
        $shipping_address = new Address($this->cart_object->id_address_delivery);

        $country_object = new Country( $shipping_address->id_country );
        $state_object = new State( $shipping_address->id_state );

        $address['name']        =   $shipping_address->firstname . " " . $shipping_address->lastname;
        $address['line1']       =   $shipping_address->address1;
        $address['line2']       =   $shipping_address->address2;
        $address['postcode']    =   $shipping_address->postcode;
        $address['suburb']      =   $shipping_address->city;
        $address['phoneNumber'] =   $shipping_address->phone;

        $address['countryCode'] =   $country_object->iso_code;

        if( !empty($shipping_address->id_state) && !empty($state_object) ) {
            $address['state']   =   $state_object->iso_code;
        }
        else {
            $address['state']   =   $shipping_address->city;
        }

        return $address;
    }
    
    /**
    * Process the Order Billing Address
    *
    * @return array
    * since 1.0.0
    */
    private function _processBillingAddress() {
        $billing_address = new Address($this->cart_object->id_address_invoice);

        $country_object = new Country( $billing_address->id_country );
        $state_object = new State( $billing_address->id_state );


        $address['name']        =   $billing_address->firstname . " " . $billing_address->lastname;
        $address['line1']       =   $billing_address->address1;
        $address['line2']       =   $billing_address->address2;
        $address['postcode']    =   $billing_address->postcode;
        $address['suburb']      =   $billing_address->city;
        $address['phoneNumber'] =   $billing_address->phone;

        $address['countryCode'] =   $country_object->iso_code;

        if( !empty($billing_address->id_state) && !empty($state_object) ) {
            $address['state']   =   $state_object->iso_code;
        }
        else {
            $address['state']   =   $billing_address->city;
        }

        return $address;
    }
    
    /**
    * Process the Order Total Amount
    *
    * @return AfterpayAmount
    * since 1.0.0
    */
    private function _processTotal() {
        $total_amount = new AfterpayAmount( round( $this->cart_object->getOrderTotal(), 2), $this->currency_code ); 
        return $total_amount;
    }

    /**
    * Process the Order Redirection Targets
    *
    * @return AfterpayRedirectUrls
    * since 1.0.0
    */
    private function _processRedirect() {

        $site_url = Tools::getHttpHost(true).__PS_BASE_URI__;
        $success_url = $site_url . "module/afterpay/return";
        $cancel_url = $site_url . "order?step=1&?=";

        $redirect_url = new AfterpayRedirectUrls($success_url, $cancel_url);
        return $redirect_url;
    }

    /**
    * Process the Order Customer Details
    *
    * @return AfterpayCustomer
    * since 1.0.0
    */
    private function _processCustomer() {
        //get Customer Data 
        $customer_id = $this->cart_object->id_customer;
        $customer = new Customer( (int) $customer_id );

        //get the Billing Phone
        $billing_address = new Address($this->cart_object->id_address_invoice);

        $consumer_data["phoneNumber"]   =   $billing_address->phone;
        $consumer_data["givenName"]     =   $customer->firstname;
        $consumer_data["surName"]       =   $customer->lastname;
        $consumer_data["email"]         =   $customer->email;

        $afterpay_customer = new AfterpayCustomer($consumer_data);

        return $afterpay_customer;
    }

    /**
    * Process the Order Discount Details
    *
    * @return AfterpayDiscount
    * since 1.0.0
    */
    private function _processDiscount( $total_discounts ) {
        //get Customer Data 
        $discount_amount = new AfterpayAmount( $total_discounts, $this->currency_code );
        $discount = new AfterpayDiscount('Discount Coupon', $discount_amount);

        return array( $discount );
    }
}