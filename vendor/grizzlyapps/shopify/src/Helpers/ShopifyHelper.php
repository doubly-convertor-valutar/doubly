<?php namespace Grizzlyapps\Shopify\Helpers;

use Grizzlyapps\Shopify\ShopifyAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ShopifyHelper
{
  use DispatchesJobs;
  
  protected $auth;

	public function __construct(ShopifyAuth $auth)
  {
      $this->auth = $auth;
  }
}