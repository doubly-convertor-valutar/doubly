<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Currency;
use Datatables;
use Grizzlyapps\Shopify\ShopifyAuth;

class CurrencyController extends Controller {

	protected $_user;

    public function __construct(ShopifyAuth $auth)
    {
        $this->_user = $auth->user();
    }

	public function anyData(){
		$currencies = $this->_user->currencies;
		$currency_list = Currency::getCurrencyList();
		foreach ($currencies as $key => $currency) {
			$currencies[$key]['DT_RowId'] = 'row'.$currency->id;
			$currencies[$key]['currency'] = $currency_list[$currencies[$key]['currency']];
		}
		
	    return Datatables::of($currencies)->make(true);

	}

	public function getCustomerCountry(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		$json = file_get_contents(env('GEO_URL').'&ip='.$ip);
		if ($json=='') {
			\Log::error('Connection failed.');
		}
		$response = json_decode($json,true);

		return response()->json(['country_code' => $response['country_code']]);
	}
	
}