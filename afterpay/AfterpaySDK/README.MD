# Afterpay SDK for PHP


__Welcome to Afterpay PHP SDK V1.0__. This repository contains Afterpay PHP SDK Code, documentation and samples to use merchant APi V1


## SDK Documentation

[ Our Afterpay-PHP-SDK Page ](https://tngith-sup.tafmo.net/Touch/paylater-php.git) includes all the documentation related to PHP SDK. Everything from Documentation, Sample Codes, to Releases. Here are few quick links to get you there faster.

* [ Afterpay-PHP-SDK Home Page ](https://tngith-sup.tafmo.net/Touch/paylater-php.git)
* [ Samples ](https://tngith-sup.tafmo.net/Touch/paylater-php.git)
* [ Installation ](https://tngith-sup.tafmo.net/Touch/paylater-php.git)
* [ Make your First SDK Call](https://tngith-sup.tafmo.net/Touch/paylater-php.git)
* [ Afterpay Developer Docs] (https://tngith-sup.tafmo.net/Touch/paylater-php.git)

## Latest Updates

- SDK v1.0 allows you to use Afterpay Merchant API V1 endpoints.
- Starting from configuration API call, Order, Payment and Refund API calls are very easy to make.
- It includes sample code for all API calls to make it easy to use.


## Prerequisites

   - PHP 7.1
   - PHPUnit 6.0 
   - [curl](http://php.net/manual/en/book.curl.php) & [json](http://php.net/manual/en/book.json.php) extensions must be enabled



## Installation and Activation

This section outlines the steps to install the Afterpay PHP SDK.

If you are upgrading to a new version of the Afterpay SDK, it is always a good practice to backup your existing SDK folder before you commence the installation steps.

1. Download a ZIP archive of this repository by clicking the "Clone or download" dropdown button, then "Download ZIP".
2. Go to AfterpayConfig.php file in Afterpay/Core folder to set all API constants. Mainly need to change MERCHANT_ID and MERCHANT_SECRET_KEY.
3. Change the API end points based on production or sandbox enviornment.
3. Follow SampleCode directory to see how to call Config, Create Order, Payment Capture and Refund API's. 
4. Use SampleCode/SamplePreAuth to retrieves a list of payment configuration that includes payment types and valid payment ranges.
5. Use SampleCode/SampleCreateOrder to create an order that is used to initiate the afterpay payment process.
6. Use SampleCode/SampleGetOrder to retrieves an individual order by token.
7. Use SampleCode/SampleCapturePayment to perform a direct payment capture and is the equivalent of capturing a credit card.
8. Use SampleCode/SampleProcessRefund to perform a full or partial refund.


