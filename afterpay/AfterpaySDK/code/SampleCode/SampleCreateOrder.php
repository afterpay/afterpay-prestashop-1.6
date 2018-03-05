<?php

//Include all Required classes
include('../Afterpay/Api/Consumer.php');
include('../Afterpay/Api/Address.php');
include('../Afterpay/Api/BillingAddress.php');
include('../Afterpay/Api/ShippingAddress.php');
include('../Afterpay/Api/Item.php');
include('../Afterpay/Api/Amount.php');
include('../Afterpay/Api/RedirectUrls.php');
include('../Afterpay/Api/Discount.php');
include('../Afterpay/Api/ShippingCourier.php');
include('../Afterpay/Api/Transaction.php');
include('../Afterpay/Api/CreateOrder.php');
include('../Afterpay/Validation/StringValidator.php');


use Afterpay\Api\Consumer;
use Afterpay\Api\Address;
use Afterpay\Api\BillingAddress;
use Afterpay\Api\ShippingAddress;
use Afterpay\Api\Item;
use Afterpay\Api\Amount;
use Afterpay\Api\RedirectUrls;
use Afterpay\Api\Discount;
use Afterpay\Api\ShippingCourier;
use Afterpay\Api\Transaction;
use Afterpay\Api\CreateOrder;
use Afterpay\Core\AfterpayConfig;


/* If configuration is successfull, proceed to create order API
* Preparation for create order
* Setup Properties for a Consumer. Create an array with consumer values
*/
$consumerArray = [];
$consumerArray['phoneNumber'] = '0422042042';
$consumerArray['givenName'] = 'Joe';
$consumerArray['surName'] = 'Consumer';
$consumerArray['email'] = 'test@afterpay.com.au';

//Create cusumer object to set the values.
$consumer = new Consumer($consumerArray);
//Validate Consumer Object
$isConsumerValid = $consumer->validateConsumer();

/** End Of Consumer Property Settings */


/* If Consumer validation is successful, proceed to create Billing and Shipping Address
* Setup Properties for Billing Address 
*/

$billingAddress = [];
$billingAddress['name'] = 'Joe Consumer';
$billingAddress['line1'] = 'Unit 1 16 Floor';
$billingAddress['line2'] = '380 LaTrobe Street';
$billingAddress['suburb'] = 'Melbourne';
$billingAddress['state'] = 'VIC';
$billingAddress['postcode'] = '3000';
$billingAddress['countryCode'] = 'AU';
$billingAddress['phoneNumber'] = '0400892011';

//Create a Billing Address Object. Pass billing address array into constructor.
$billingAddressObj = new BillingAddress($billingAddress);
//Validate Billing Address Object
//$isBillingAddressValid = $billingAddressObj->validateBillingAddress();

/** End Of Billing Address Property settings */


/* If Billing validation is correct, proceed to create Shipping Address
* Setup Shipping Address
* Shipping Amount and Shipping Courier
*/

$shippingAddress = [];
$shippingAddress['name'] = 'Joe Consumer';
$shippingAddress['line1'] = 'Unit 1 16 Floor';
$shippingAddress['line2'] = '380 LaTrobe Street';
$shippingAddress['suburb'] = 'Melbourne';
$shippingAddress['state'] = 'VIC';
$shippingAddress['postcode'] = '3000';
$shippingAddress['countryCode'] = 'AU';
$shippingAddress['phoneNumber'] = '0400892011';

//Create a Shipping Address Object. Pass shipping address array into constructor.
$shippingAddressObj = new ShippingAddress($shippingAddress);
//Validate Shipping Address Object
////Setup Shipping Amount if applicable (optional). No need to include for free shipping
$shippingAmount = new Amount('10.00','AUD');
//Shipping Courier Details. Name and Priority
$shippingCourier = new ShippingCourier('FedEx','Standard');

/** End Of Shipping Address Property settings */



/* If Shipping validation is correct, proceed to create Item Object
* Setup Item Details
* 
*/
//Pass Price and Currency in Amount Constructor
$item1Price = new Amount('10.00','AUD');
//Setup properties for purchased items. Pass name, SKU, Quantity and Price Object in Constructor
//Pass Multiple items if required.
$item1 = new Item('widget', '123412234', 1, $item1Price);
$item2 = new Item('widget1', '123412234', 1, $item1Price);
$item3 = new Item('widget2', '123412234', 1, $item1Price);
//Setup Tax Amount if applicable (optional)
$taxAmount = new Amount('10.00','AUD');
//Setup Total Order Amount
$totalAmount = new Amount('10.00','AUD'); 

/*End of Item Setup */

//Setup Confirm and Cancel URLs for order success and failure. Params are confirm URL and Cancel URL.
$redirectUrl = new RedirectUrls('http://php-docker.local:8080/SampleCode/Confirm.php','http://php-docker.local:8080/SampleCode/Cancel.php');

//Setup Discount Amount, if applicable. It's optional to set. Pass Discount name and Amount
$discountAmount1 = new Amount('10.00','AUD');
$discount1 = new Discount('Returning Customer Coupon',$discountAmount1);
$discount2 = new Discount('Returning Customer Coupon1',$discountAmount1);
$discount3 = new Discount('Returning Customer Coupon2',$discountAmount1);




//Build a complete transaction
$transaction = new Transaction();
$transaction->setTotalAmount($totalAmount)
            ->setConsumer($consumer)
            ->setBilling($billingAddress)
            ->setShipping($shippingAddress)
            ->setCourier($shippingCourier)
            ->setItems(array($item1,$item2,$item3))
            ->setDiscounts(array($discount1,$discount2,$discount3))
            ->setMerchantUrl($redirectUrl)
            ->setMerchantReference('merchantOrder-123433333')
            ->setTaxAmount($taxAmount)
            ->setShippingAmount($shippingAmount);


//Create Object of CreateOrder.
$createOrderObj = new CreateOrder();

//Call Process order Function.
$createOrderObj->processOrder($transaction);

?>
