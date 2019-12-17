<?php namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use ThemeHelper;
use Grizzlyapps\Shopify\ShopifyAuth;

class FaqController extends Controller {

	protected $_user;
	protected $_auth;

    public function __construct(ShopifyAuth $auth)
    {
        $this->_auth = $auth;
        $this->_user = $this->_auth->user();
    }

	public function index()
	{
		$title = 'FAQ';
		$themes = ThemeHelper::getThemes();
		$adapted = false;
		if (in_array($this->_user->getThemeStoreId(),$themes)) {
			$adapted = true;
		}

		$hasComma = '';
        $shop = $this->_auth->getShop();
        $accessToken = $this->_auth->getAccessToken();
        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);
		$data = $shopify->call(['URL' => '/admin/shop.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'money_with_currency_format,money_format']]);           
		if (strpos($data['shop']['money_with_currency_format'], 'comma')!==false && strpos($data['shop']['money_format'], 'comma')!==false) {
			$hasComma = '-comma';
		}

		//if span tags haven't been added to the prices show dialog modal
		$showPriceTagNotification = false;       
		if ((strpos($data['shop']['money_with_currency_format'], '<span class=doubly>')===false && strpos($data['shop']['money_with_currency_format'], '<span class=money>')===false) || strpos($data['shop']['money_with_currency_format'], '</span>')===false || (strpos($data['shop']['money_format'], '<span class=doubly>')===false && strpos($data['shop']['money_format'], '<span class=money>')===false) || strpos($data['shop']['money_format'], '</span>')===false) {
			$showPriceTagNotification = true;
		}
		//if Providence theme don't check for "money"/"doubly" class, it uses "price" class
		if ($this->_user->getThemeStoreId()==587) {
			$showPriceTagNotification = false;	
		}

		return view('pages.faq')->with(compact('title', 'adapted', 'hasComma', 'showPriceTagNotification'));
	}

}