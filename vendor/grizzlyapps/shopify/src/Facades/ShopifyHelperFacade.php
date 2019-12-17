<?php

namespace Grizzlyapps\Shopify\Facades;

use Illuminate\Support\Facades\Facade;

class ShopifyHelperFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { 
    	return 'shopify.helper'; 
    }

}
