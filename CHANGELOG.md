# Afterpay PrestaShop Module Changelog

### Release Name: Version 1.0.0
#### Release date: 05 Mar 2018
#### Platform: PrestaShop 1.6 

### Supported Editions and Versions:
- PrestaShop 1.6.1.17 and later
- Afterpay-PrestaShop1.6 module v1.0.0 has been verified against a new instance of PrestaShop 1.6.1.17
- https://github.com/PrestaShop/PrestaShop/releases/tag/1.6.1.17

### Supported Markets:
- Australia (AUD currency)

### Highlights
Version 1.0.0 of the Afterpay-PrestaShop1.6 module introduces:
- Afterpay transaction processing (orders and refunds) – Australia.
- Transaction Integrity Check.
- Afterpay asset display on PrestaShop website.
- Afterpay configuration in PrestaShop Back Office.
- Afterpay module logging.

#### Afterpay transaction processing (orders and refunds) - Australia
- Access to the Afterpay Payment Gateway via Afterpay Merchant API V1.
- Following a successful Afterpay payment capture, the below records are created in PrestaShop Back Office:
  * PrestaShop Order record with status of 'Payment accepted'
  * PrestaShop Invoice document linked to Order record
- PrestaShop order refunds (full value) trigger a call to the Afterpay API to process the refund.
- PrestaShop order refunds (partial value) are not supported in this version (1.0.0).
- Afterpay Merchant Portal provides the functionality to perform order refunds (partial or full value).

#### Transaction Integrity Check
- To verify the integrity of each transaction, a Transaction Integrity Check has been implemented.
- The Transaction Integrity Check compares the below values at time of checkout against the value present prior to payment capture:
  * Afterpay Token ID
  * PrestaShop Quote total amount.
- In the instance of a discrepancy between these values, the transaction is cancelled and no payment capture attempts will be made.

#### Afterpay asset display on PrestaShop website
- Afterpay installment detail displayed on PrestaShop product pages.
- Afterpay is included as a Payment Method on PrestaShop checkout page 'Payment' step.
- Afterpay installment detail on the Payment Submission page.
- Afterpay Lightbox modal available on PrestaShop product pages.

#### Afterpay configuration in Prestashop Back Office
- Afterpay module configuration available under:
  * PrestaShop Back Office > Modules > Modules & Services > Installed Modules.
- Afterpay configuration includes:
  * Enable / Disable module.
  * Afterpay Merchant ID.
  * Afterpay Merchant Key.
  * API Mode.

#### Afterpay configuration in Prestashop Back Office
- Afterpay module introduces transaction and validation logging into the PrestaShop Back Office log.
- Afterpay logging includes:
  * Afterpay transaction result - Approved.
  * Afterpay transaction result - Declined.
  * Validation of Merchant ID & Merchant Key combination.