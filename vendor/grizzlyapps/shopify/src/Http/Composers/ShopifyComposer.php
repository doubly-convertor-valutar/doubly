<?php

namespace Grizzlyapps\Shopify\Http\Composers;

use Illuminate\Contracts\View\View;
use Grizzlyapps\Shopify\ShopifyAuth;

class ShopifyComposer
{
    public function __construct(ShopifyAuth $auth)
    {
        $this->_auth = $auth;
    }

	public function compose(View $view) 
	{
		$plan = 0;
		$name = 'Unregistered Client';
		$email = 'None Provided';
		if (!is_null($this->_auth->user())) {
			$plan = $this->_auth->user()->getChargeId();
			$name = $this->_auth->user()->getName();
			$email = $this->_auth->user()->getEmail();
		}
		$shop = $this->_auth->getShop();

		$view->with(compact('shop', 'plan', 'name', 'email'));
	}

}