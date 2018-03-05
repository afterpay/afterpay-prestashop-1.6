{*
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
*}

<section>
	{if isset($afterpay_error) && $afterpay_error != "" }
		<div class="alert alert-danger">
			{$afterpay_error}
		</div>
	{/if}
	<div class="payment-method" id="afterpaypayovertime-method">
	    <div class="payment-method-content" id="afterpaypayovertime">
	        <div class="payment-method-note">
	            <div style="">
	            	<img src="{$afterpay_base_url}modules/afterpay/images/payment_checkout.png" alt="{l s='Pay by Afterpay' mod='afterpay'}"/>
	                <h3>
	                    Four interest-free payments totalling:
	                    <em><span class="afterpay_total_amount">{$afterpay_order_total}</span></em>
	                </h3>
	                <ul class="instalment">
	                    <li>First instalment - {$afterpay_instalment_breakdown}</li>
	                    <li>2 weeks later - {$afterpay_instalment_breakdown}</li>
	                    <li>4 weeks later - {$afterpay_instalment_breakdown}</li>
	                    <li>6 weeks later - {$afterpay_instalment_breakdown_last}</li>
	                </ul>
	            </div>
	            <h6>
	               	<u><a href="http://www.afterpay.com/terms/" target="_blank">Terms & Conditions</a></u>
	            </h6>
	        </div>
	        <div class="payment-method-content afterpay-checkout-redirect">
	            <div class="instalment-footer">

	                <br/><hr/>

			        <form class="afterpay-form" action="{$link->getModuleLink('afterpay', 'validation', [], true)|escape:'html'}" method="post">
									
						<p class="cart_navigation" id="cart_navigation">
							<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Other payment methods' mod='afterpay'}</a>
							<input type="submit" value="{l s='Pay with Afterpay' mod='afterpay'}" class="exclusive_large"/>
						</p>
					</form>
	            </div>
	        </div>
	    </div>
	    <div style="clear: both">&nbsp;</div>
	</div>

</section>