<?php
/*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

/**
 * @since 1.5.0
 */

require_once(_PS_TOOL_DIR_.'mobile_Detect/Mobile_Detect.php');

class AfterpayPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
	public $display_column_left = false;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		$cart = $this->context->cart;
		if (!$this->module->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');

        $this->context->smarty->assign( "afterpay_order_total", $this->_getCurrentCartTotalDisplay() );
        $this->context->smarty->assign( "afterpay_instalment_breakdown", $this->_getCurrentInstalmentsDisplay() );
        $this->context->smarty->assign( "afterpay_instalment_breakdown_last", $this->_getCurrentInstalmentsDisplayLast() );

		$this->context->smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),
			'cust_currency' => $cart->id_currency,
			'currencies' => $this->module->getCurrency((int)$cart->id_currency),
			'total' => $cart->getOrderTotal(true, Cart::BOTH),
			'isoCode' => $this->context->language->iso_code,
			'this_path' => $this->module->getPathUri(),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));

		$afterpay_error = Tools::getValue('afterpay_error');
        $this->context->smarty->assign('afterpay_error', $afterpay_error);

        if( $this->_getDeviceType() == "mobile" ) {
            $this->setTemplate("payment_infos_mobile.tpl");
        }
        else {
            $this->setTemplate("payment_infos.tpl");
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

        return $this->context->currency->iso_code . " " . $this->context->currency->sign . $instalment;
    }
    
    /**
     * Display function for Last Payment Breakdown
     *
     * @return string
     * since 1.0.0
     */
    private function _getCurrentInstalmentsDisplayLast( $amount = NULL ) {

        if( empty($amount) ) {
            $cart = $this->context->cart;
            $amount = $cart->getOrderTotal();
        }

        $prev_instalments = round($amount / 4, 2, PHP_ROUND_HALF_UP);
        $instalment = $amount - 3 * $prev_instalments;

        return $this->context->currency->iso_code . " " . $this->context->currency->sign . $instalment;
    }
    
    /**
     * Display function for Last Payment Breakdown
     *
     * @return string
     * since 1.0.0
     */
    private function _getDeviceType() {

        $this->mobile_detect = new Mobile_Detect();
        $mobile_class = 'desktop';
        if ($this->mobile_detect->isMobile()){
            $mobile_class = 'mobile';
        }

        return $mobile_class;
    }
}
