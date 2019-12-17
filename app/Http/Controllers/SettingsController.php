<?php namespace App\Http\Controllers;

use App;
use App\Currency;
use App\Http\Controllers\Controller;
use App\Settings;
use CurrencyHelper;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SettingFormRequest;
use Grizzlyapps\Shopify\ShopifyAuth;
use ShopifyLog;

class SettingsController extends Controller {

	protected $_user;
	protected $_auth;

    public function __construct(ShopifyAuth $auth)
    {
        $this->_auth = $auth;
        $this->_user = $this->_auth->user();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $shop = $this->_auth->getShop();
        $accessToken = $this->_auth->getAccessToken();
        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);

		$unsortedSettings = $this->_user->settings->toArray();
		$settings = array();
		foreach ($unsortedSettings as $setting) {
			$settings[$setting['key']] = $setting['value'];
		}

		$boolean_array = array('1' => 'Yes','0' => 'No');
		$currency_items = $this->_user->currencies;
		$currency_list = Currency::getCurrencyList();
		$shopCurrency[$settings['default_currency']] = $currency_list[$settings['default_currency']];
		unset($currency_list[$settings['default_currency']]);
		$currency_list = array_merge($shopCurrency, $currency_list);
		$default_currency = $shopCurrency[$settings['default_currency']];

		$title = 'Settings';
		$user_id = $this->_auth->getUser()->getId();

		//if span tags haven't been added to the prices show dialog modal
		$showPriceTagNotification = false;
		$data = $shopify->call(['URL' => '/admin/shop.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'money_with_currency_format,money_format']]);           
		if ((strpos($data['shop']['money_with_currency_format'], '<span class=doubly>')===false && strpos($data['shop']['money_with_currency_format'], '<span class=money>')===false && strpos($data['shop']['money_with_currency_format'], '<span class="doubly">')===false && strpos($data['shop']['money_with_currency_format'], '<span class="money">')===false) || strpos($data['shop']['money_with_currency_format'], '</span>')===false || (strpos($data['shop']['money_format'], '<span class=doubly>')===false && strpos($data['shop']['money_format'], '<span class=money>')===false && strpos($data['shop']['money_format'], '<span class="doubly">')===false && strpos($data['shop']['money_format'], '<span class="money">')===false) || strpos($data['shop']['money_format'], '</span>')===false) {
			$showPriceTagNotification = true;
		}
		//if Providence theme don't check for "money"/"doubly" class, it uses "price" class
		if ($this->_user->getThemeStoreId()==587) {
			$showPriceTagNotification = false;	
		}

		$hasReviewed = ShopifyLog::hasReviewed($user_id);

		return view('pages.settings')->with(compact('settings','currency_list','currency_items','boolean_array', 'title', 'showPriceTagNotification','user_id','default_currency', 'hasReviewed'));
	}

	/**
	 * Store a newly created resource in storage. (Delete the existing resource, if there was any before saving)
	 *
	 * @return Response
	 */

	public function store(SettingFormRequest $request)
	{
		$inputs   = $request->all();

		//Remove unwanted POST fields
		if(array_key_exists('users-table_length', $inputs)){
			unset($inputs['users-table_length']);
		}

		//Save settings
		$oldSettings = $this->_user->settings()->lists('value', 'key')->toArray();
		$autoSwitch = 1;

		$this->_user->settings()->delete();
		foreach($inputs as $key=>$val){
			if($key == '_token' || $key == 'remove_currency_ids' || $key == 'json_data') continue;
			if($key=='auto_switch') $autoSwitch = $val;	
			
			$settings = new Settings;
			$settings->key   = $key;
			$settings->value = $val;
			$this->_user->settings()->save($settings);
		}

		//reset cookie if auto_switch value changed or if it's set to true
		$settings = new Settings;
		$settings->key = 'cookie_name';
		if ($autoSwitch==1 || $autoSwitch!=$oldSettings['auto_switch'] || !isset($oldSettings['cookie_name'])) {
			$settings->value = 'currency'.strtotime(date('Y-m-d H:i:s'));
		} else {
			$settings->value = $oldSettings['cookie_name'];
		}
		$this->_user->settings()->save($settings);

		//re-add default currency
		$settings = new Settings;
		$settings->key = 'default_currency';
		$settings->value = $oldSettings['default_currency'];
		$this->_user->settings()->save($settings);

		//Remove deleted currencies
		if($inputs['remove_currency_ids']!=''){
			$val = explode(',', $inputs['remove_currency_ids']);
			foreach ($val as $currencyId) {
				$currency = $this->_user->currencies()->find($currencyId);
				if (!is_null($currency)) {
					$currency->delete();
				}
			}
		}

		//Store newly added currencies
		$val = json_decode($inputs['json_data']);
		foreach ($val as $key2 => $value2) {
			if($value2->id[0] == 'n'){
				if(!$this->validate_currency($value2->currency)){
					flash()->error('Duplicate currency found!');
					return Redirect::back();
				}

				$currency = new Currency;
			} else {
				$id = (int) $value2->id;
				$currency = $this->_user->currencies()->find($id);
			}

			if ($currency && isset($value2->currency) && isset($value2->position)) {
				$currency->currency = $value2->currency;
				$currency->position = $value2->position;
				$this->_user->currencies()->save($currency);
			}
		}

		//if default currency isn't in currencies table, add it
		if (!in_array($oldSettings['default_currency'], $this->_user->currencies()->lists('currency')->toArray())) {
			$currency = new Currency;
			$currency->currency = $oldSettings['default_currency'];
			$currency->position = 0;
			$this->_user->currencies()->save($currency);
		}

        CurrencyHelper::createCurrencySwitcherLiquid();

		flash()->success('Saved successfully!');
		return Redirect::back();

	}

	public function validate_currency($new_currency){
		
		$currencies = $this->_user->currencies()->lists('currency')->toArray();

		if(in_array($new_currency, $currencies)){
			return false;
		}else{
			return true;
		}
	}
}