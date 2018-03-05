<?php

namespace Afterpay\Api;

use Afterpay\Validation\StringValidator;

/**
	* Class RedirectUrls
	*
	* Set of redirect URLs you provide only for Afterpay-based payments.
	*
	* @package Afterpay\Api
	*
	* @property string redirectConfirmUrl
	* @property string redirectCancelUrl
*/
class RedirectUrls 
{
	/**
    * Redirect Confirm Url
    */
    public $redirectConfirmUrl;
    /**
    * Redirect Cancel Url
    */
    public $redirectCancelUrl;

	/**
    * Default Constructor
    * 
    * @param string $confirmUrl
    * @param string $cancelUrl
    * 
    */
    public function __construct($confirmUrl,$cancelUrl)
    {
        $this->setRedirectConfirmUrl($confirmUrl);
        $this->setRedirectCancelUrl($cancelUrl);

    }
	/**
	* Url where the consumer would be redirected to after approving the payment. 
	*
	* @param string redirectConfirmUrl
	* @throws \InvalidArgumentException
	* @return $this
	*/
	protected function setRedirectConfirmUrl($redirectConfirmUrl)
	{
		$this->redirectConfirmUrl = $redirectConfirmUrl;
		return $this;
	}
	/* Url where the consumer would be redirected to after approving the payment. 
	*
	* @return string
	*/
	protected function getRedirectConfirmUrl()
	{
		return $this->redirectConfirmUrl;
	}
	/**
	* Url where the consumer would be redirected to after canceling the payment. 
	*
	* @param string $redirectCancelUrl
	* @throws \InvalidArgumentException
	* @return $this
	*/
	protected function setRedirectCancelUrl($redirectCancelUrl)
	{	
		$this->redirectCancelUrl = $redirectCancelUrl;
		return $this;
	}
	/**
	* Url where the consumer would be redirected to after canceling the payment. 
	*
	* @return string
	*/
	protected function getRedirectCancelUrl()
	{
		return $this->redirectCancelUrl;
	}
	/**
	* Validate confirm URL and Cancel URL
	* Throw Exception if fails
	*
	* @return null
	*/
	public function validateRedirectUrl()
	{
		foreach ($this as $key => $value) 
		{
			StringValidator::validate($key,$value);
       	}
       return null;
	}	
	
}
