<?php

namespace Grizzlyapps\Shopify\Http\Validators;

use Illuminate\Validation\Validator as IlluminateValidator;

class ShopifyValidator extends IlluminateValidator {

	private $_customMessages = array(
		"shopify_url" => "Please enter a valid Shopify store url i.e. example.myshopify.com"
	);

	public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
		parent::__construct($translator, $data, $rules, $messages, $customAttributes);
		$this->_setCustomMessages();
	}

	/**
	 * Setup any custom error messages
	 *
	 * @return void
	 */
	protected function _setCustomMessages() {
		//setup our custom error messages
		$this->setCustomMessages( $this->_customMessages );
	}

	/**
	 * Allow only A-Z, a-z, 0-9 and hyphens
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	protected function validateShopifyUrl( $attribute, $value ) {
		$urlSegments = explode('.', $value);
		if (count($urlSegments)==3) {
			return (preg_match( "/^[A-Za-z0-9-]+$/", $urlSegments[0]) && $urlSegments[1]=='myshopify' && $urlSegments[2]=='com') ? true : false;
		} else if (count($urlSegments)==1) {
			return (preg_match( "/^[A-Za-z0-9-]+$/", $urlSegments[0]));
		}

		return false;
	}

}