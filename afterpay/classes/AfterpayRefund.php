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


require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/ProcessRefund.php' );
require_once( _PS_MODULE_DIR_ . 'afterpay/AfterpaySDK/code/Afterpay/Api/Amount.php' );

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

use Afterpay\Api\ProcessRefund as AfterpayProcessRefund;
use Afterpay\Api\Amount as AfterpayAmount;


if (!defined('_PS_VERSION_')) {
    exit;
}

class AfterpayRefund
{
    private $afterpay_process_refund;

    public function __construct($merchant_id, $merchant_secret, $mode, $user_agent = '', $currency_code = 'AUD') {

        $config =   array(
                        'merchantId'        =>  $merchant_id,
                        'merchantSecret'    =>  $merchant_secret,
                        'mode'              =>  $mode,
                        'sdkName'           =>  $user_agent
                    );

        $this->afterpay_process_refund = new AfterpayProcessRefund($config);
    }

    /*----------------------------------------------------------------------------------------------------
                                            
    ----------------------------------------------------------------------------------------------------*/

    public function doRefund( $afterpay_transaction_id, $amount, $currency_code ) {
        $amount = new AfterpayAmount( $amount, $currency_code );
        $results = $this->afterpay_process_refund->processOrderRefund( $afterpay_transaction_id, $amount );

        return $results;
    }
}