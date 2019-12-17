<?php namespace App\Http\Controllers;

use App\Country;
use App\Currency;
use App\Http\Controllers\Controller;
use CurrencyHelper;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Grizzlyapps\Shopify\ShopifyAuth;

class CurrencyByCountryController extends Controller {

	protected $_user;

    public function __construct(ShopifyAuth $auth)
    {
        $this->_user = $auth->user();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// $countries = Country::all()->toArray();

		$currency_list = Currency::getCurrencyList();
		$currency_items = array();
		foreach ($this->_user->currencies as $key => $value) {
			$currency_items[] = $currency_list[$value['currency']];
		}
		asort($currency_items);
		$defaultCurrency = $this->_user->settings()->select('value')->where('key','=','default_currency')->first();
		$title = 'Currency By Country';

		return view('pages.currency-by-country')->with(array('currency_list' => $currency_list, 'currency_items' => $currency_items, 'defaultCurrency' => $currency_list[$defaultCurrency['value']], 'title' => $title));
	}

    public function loadAll(){
    	$countries = Country::get();
		$currency_list = Currency::getCurrencyList();
    	$currencyByCountry = $this->_user->countries;
    	if (count($currencyByCountry)>0) {
    		foreach ($currencyByCountry as $country) {
    			$userCountries[$country->country_code] = $currency_list[$country->currency];
    		}
		}
    	foreach ($countries as $key => $country) {
    		if (isset($userCountries[$country->country_code])) {
    			$countries[$key]['currency_code'] = $userCountries[$country->country_code];
    		} else {
    			$countries[$key]['currency_code'] = $currency_list[$country->currency_code];
    		}
    	}
        return Datatables::of($countries)->make(true);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$inputs   = $request->all();

		//Remove unwanted POST fields
		if(array_key_exists('users-table_length', $inputs)){
			unset($inputs['users-table_length']);
		}

		$this->_user->countries()->delete();
		$currency_list = Currency::getCurrencyList();

		//Store newly added currencies
    	$countryCurrency = array();
    	foreach (Country::get() as $country) {
    		$countryCurrency[$country->country_code] = $country->currency_code;    			
    	}
		$val = json_decode($inputs['json_data']);
		foreach ($val as $key => $value) {
			if ($countryCurrency[$value->country_code]!=$value->currency) {
				$currency = $this->_user->countries()->findOrNew($value->country_code,array('country_code'));

				$currency->currency = $value->currency;
				$currency->country_code = $value->country_code;

				$this->_user->countries()->save($currency);
			}
		}

		//reset cookie if auto_switch value is set to true
		$settings = $this->_user->settings()->where('key', 'auto_switch')->first()->toArray();
		$autoSwitch = $settings['value'];
		if ($autoSwitch==1) {
			$settings = $this->_user->settings()->where('key', 'cookie_name')->first();
			$settings->key = 'cookie_name';
			$settings->value = 'currency'.strtotime(date('Y-m-d H:i:s'));
			$this->_user->settings()->save($settings);
		}
		
        CurrencyHelper::createCurrencySwitcherLiquid();

		flash()->success('Saved successfully!');
		return Redirect::back();

	}

}
