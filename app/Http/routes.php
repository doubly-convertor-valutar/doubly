<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('currency/load-all',['as' => 'loadAllCurrencies', 'uses' => 'CurrencyController@anyData']);
Route::get('getCountry', ['middleware' => 'cors', 'uses' => 'CurrencyController@getCustomerCountry']);

Route::get('/','SettingsController@index');
Route::resource('settings','SettingsController');
Route::resource('currency','CurrencyController');

//Currency By Country
Route::get('country/load-all',['as' => 'loadAllCountries', 'uses' => 'CurrencyByCountryController@loadAll']);
Route::resource('currency-by-country','CurrencyByCountryController');

//FAQ
Route::get('faq','FaqController@index');



